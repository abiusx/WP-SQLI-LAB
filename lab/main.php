<?php
require_once __DIR__."/model/base.php";
/**
 * extracts class names inside a php code file to be later instantiated
 */
function GetClasses($file)
{
    $php_code = file_get_contents ( $file );
    $classes = array ();
    $namespace="";
    $tokens = token_get_all ( $php_code );
    $count = count ( $tokens );
    for($i = 0; $i < $count; $i ++)
    {
        if ($tokens[$i][0]===T_NAMESPACE)
        {
            for ($j=$i+1;$j<$count;++$j)
            {
                if ($tokens[$j][0]===T_STRING)
                    $namespace.="\\".$tokens[$j][1];
                elseif ($tokens[$j]==='{' or $tokens[$j]===';')
                    break;
            }
        }
        if ($tokens[$i][0]===T_CLASS)
        {
            for ($j=$i+1;$j<$count;++$j)
                if ($tokens[$j]==='{')
                    $classes[]=$namespace."\\".$tokens[$i+2][1];
        }
    }
    return $classes;
        //is_subclass_of(class, parent_class,/* allow first param to be string */true) 
}
class PluginNotFoundException extends Exception {}
class ExploitsSetup extends BaseExploit
{
	function __construct()
	{
		require_once $this->path()."wp-config.php";
		ob_end_clean();
		$this->db=new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		
		$this->Install();
		
		$this->tablePrefix=$table_prefix;
		$this->Init();
	}
	/**
	 * enables Wordpress Magic Quotes, which is enabled by default and
	 * prevents most SQL injections (anything involving a quote)
	 */
	function EnableWPQuotes()
	{
		$t=file_get_contents($this->path()."/wp-settings.php");
		$t=str_replace("//MAGIC QUOTES DISABLED", "wp_magic_quotes();", $t);
		file_put_contents($this->path()."wp-settings.php", $t);
	}
	/**
	 * disables wordpress magic quotes, to test exploits that can't bypass it.
	 */
	function DisableWPQuotes()
	{
		$t=file_get_contents($this->path()."/wp-settings.php");
		$t=str_replace("wp_magic_quotes();", "//MAGIC QUOTES DISABLED", $t);
		file_put_contents($this->path()."wp-settings.php", $t);
	}
	function DisablePlugins()
	{
		return $this->db->query("UPDATE {$this->tablePrefix}options SET option_value='' WHERE
			option_name='active_plugins'  LIMIT 1"); //disable all plugins

	}
	/**
	 * this installs the new plugins that are added to the folder, by activating their hooks
	 * and then de-activating them. This allows them to create their database structurs
	 * and other necessary things before being tested.
	 * forced on every launch, cached, and useful for new setups.
	 */
	function Install()
	{
		$this->DisablePlugins();
		#FIXME: can't install all together, do one by one (see what wordpress does), some are not installed
		#properly, such as zotpress
		$this->LoginAsAdmin();
		if (file_exists(__DIR__."/installed.cache"))
			$installed=unserialize(file_get_contents(__DIR__."/installed.cache"));
		else
			$installed=array();
		$flag=false;
		foreach (glob($this->path()."wp-content/plugins/*") as $plugin)
		{
			if (!is_dir($plugin)) continue;
			$basename=basename($plugin);
			try {
				$info=$this->PluginInfo($basename);
				$file=substr($info['file'],strlen($this->path()."wp-content/plugins/"));
			}
			catch (PluginNotFoundException $e)
			{
				echo $plugin." Not Found".PHP_EOL;
				continue;
			}
			if (!$installed[$basename])
			{
				echo "Installing plugin '{$info['Name']}'...";
				if (!is_plugin_active($file))
					if ($r=activate_plugin($file))
					{
						echo "Error: ";
						print_r($r->errors);
					}
					else
					{

						$installed[$basename]=true;
						echo " done.".PHP_EOL;
					}
				else
				{
					echo " already done.".PHP_EOL;	
					$installed[$basename]=true;
				}
					
				// deactivate_plugins($info['file']);
				//no need to de-activate, its done via database and loading
				//calling deactivate will make them erase themselves
				$flag=true;
				$this->DisablePlugins();
				file_put_contents(__DIR__."/installed.cache", serialize($installed));
			}
		}
		if ($flag==true)
			die("New plugins were installed. Rerun the script to install more of them.\n");
	}
	/**
	 * login as admin is needed to install plugins in wordpress
	 */
	 protected function LoginAsAdmin()
	{
		#needed to use activate_plugin
		ob_start();
		$user = get_userdatabylogin( "admin" );
        $user_id = $user->ID;
        wp_set_current_user( $user_id, $user_login );
        wp_set_auth_cookie( $user_id );
        define(WP_ADMIN,true);
        ob_end_clean();
	}
	/**
	 * given a plugin folder, returns all information about that plugin
	 */
	protected function PluginInfo($plugindir)
	{
		require_once $this->path()."wp-admin/includes/plugin.php";
		$pfile=null;
		foreach (glob ($this->path()."wp-content/plugins/".$plugindir."/*.php") as $file)
		{
			$info=(get_plugin_data($file));
			if (strlen($info['Name']))
			{
				$pfile=$file;
				break;
			}
		}
		if (!$pfile)
			throw new PluginNotFoundException("Could not find plugin {$plugindir}.");
		$info['file']=$pfile;
		return $info;		
	}
	/**
	 * activates a wordpress plugin without calling any hooks,
	 * just by adding it to the list of active wordpress plugins in
	 * wordpress database.
	 * the plugin needs to be activated properly before, to have been installed.
	 * @see Install
	 */
	function ActivatePlugin($plugindir)
	{
		$info=$this->PluginInfo($plugindir);
		$pfile=$info['file'];
		$shortpfile=substr($pfile,strlen($this->path()."wp-content/plugins/"));
		$pluginsArray=array($shortpfile);
		$pluginData=serialize($pluginsArray);
		if (!$this->db->query("UPDATE {$this->tablePrefix}options SET option_value='{$pluginData}' WHERE
			option_name='active_plugins'  LIMIT 1") )
			throw new Exception("Could not update plugin data on database!");


		return $info;

	}	
	/**
	 * inits the framework by calculating the roundtrip time and the blind SQL
	 * timeout based on the roundtrip time and the database speed.
	 */
	protected function Init()
	{
		$this->DisablePlugins();
		echo "Calculating typical roundtrip time to server...\n";
		$time=0;
		$count=5;
		for ($i=0;$i<$count;++$i)
		{
			$this->timein();
			$this->curl(self::$url);
			$time+=$this->timeout();
		}
		self::$roundtrip=$time/$count;
		echo "Average roundtrip was ".self::$roundtrip." seconds.\n";
		self::$threshold=self::$roundtrip*BaseExploit::$thresholdMultiplier;

		echo "Calculating the appropriate benchmark count for mysql to use in exploits...\n";
		do {
			$this->timein();
			$this->db->query("SELECT IF(2>1,{$this->benchmark()},0)");
			$dif=$this->timeout();
			echo self::$benchmark."={$dif}s\n";
			self::$benchmark=(int)(self::$benchmark* (self::$threshold/$dif));
			flush();
		} while ( $dif<self::$threshold or abs($dif - self::$threshold)>.1);
		echo "Benchmark count ".self::$benchmark." is appropriate, it takes {$dif} seconds.\n";
	}
	function test() {return true;}
}
require_once __DIR__."/config.php";
if (!ini_get("short_open_tag"))
	die("You need to enable short tags to install plugins.".PHP_EOL);


$specificExploit=null;
for ($i=1;$i<$argc;++$i)
{
	if($argv[$i]!="-v" and $argv[$i]!="-t")
		$specificExploit=$argv[$i];
}
if (in_array("-v",$argv))
	BaseExploit::$verbose=true;

$setup=new ExploitsSetup();
echo str_repeat("-",80)."\n";
if (BaseExploit::$logdir)
	system("rm -rf ".BaseExploit::$logdir."/*");
$count=0;$exploitable=0;
//iterate through exploits and test them
foreach (glob(__DIR__."/exploits/*.php") as $file)
{
	flush();
	require_once $file;
	$classes=GetClasses($file);
	$selectedClass=null;
	foreach ($classes as $class)
		if (is_subclass_of($class,"BaseExploit",true))
			$selectedClass=$class;
	if (!$selectedClass) continue; //no class found

	$obj=new $selectedClass;
	
	if ($obj->skip) continue; //plugins marked as not working are skipped
	
	if ($specificExploit && $obj->name()!=$specificExploit) continue;
	if (!($obj->name())) //it doesn't have a folder name!
	{
		echo ("Invalid exploit {$file}.\n");
		continue;
	}
	
	$info=$setup->ActivatePlugin($obj->name());
	$title="{$info['Name']} {$info['Version']}";
	if ($obj->magic)
	{
		$title="*".$title;	
		$setup->DisableWPQuotes();
	}
	$setup->timein();
	$isExploitable=$obj->test();
	$time=$setup->timeout();
	$status=($isExploitable?"Exploitable":"Secure");
	$dotlen=80-(strlen($title)+strlen($status));
	if ($dotlen<3)
		$dotlen=80+$dotlen;
	echo $title.str_repeat(".",$dotlen).$status."\n";
	if (in_array("-t", $argv)) echo $time.PHP_EOL;
	$count++;
	if ($isExploitable)
		$exploitable++;
	if ($obj->magic)
		$setup->EnableWPQuotes();
	if (BaseExploit::$logdir)
	{
		system("mkdir -p ".BaseExploit::$logdir."/{$obj->name()}");
		system("mv ".BaseExploit::$logdir."/*.* ".BaseExploit::$logdir."/{$obj->name()}/ >/dev/null 2>&1");
	}
}
echo str_repeat("-",80)."\n";
if (!$specificExploit) $setup->DisablePlugins();
echo "Result: {$count} plugins, {$exploitable} exploited.\n";