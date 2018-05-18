<?php
namespace Sch;

class Project{
    public $user;
    public $repoName;

    public $forks = 0;
    public $stars = 0;
    public $watchers = 0;
    public $latestRelease = 0;
    public $openPullRequest = 0;
    public $closedPullRequest = 0;

    function __construct($user, $repoName){
        $this->user = $user;
        $this->repoName = $repoName;
        $this->getData();
    }

    public static function expose()
    {
        return get_class_vars(__CLASS__);
    }

    public static function getTitles()
    {
        $f3 = \Base::instance();
        $tr = array();
        
        foreach( self::expose() as $key => $val ){
            $str = $f3->get( $key );
            $tr[] = ( $str ? $str : $key);;
        }

        return $tr;
    }
    
    function getData()
    {
        $url = "https://api.github.com/search/repositories?q=user%3A{$this->user}+repo%3A{$this->repoName}+{$this->repoName}";
        $request=\Web::instance()->request($url);

        $data = json_decode( $request['body'] );

        foreach($data->items as $item){
            $this->stars += $item->stargazers_count;
            $this->forks += $item->forks_count;
            $this->watchers += $item->watchers_count;
            $this->latestRelease = $item->updated_at; 
        }
        
        $pullsUrl = $this->removeParams($item->pulls_url);

        $this->openPullRequest = $this->getPulls( $pullsUrl, "open" ) ;
        $this->closedPullRequest = $this->getPulls( $pullsUrl, "closed" ) ;;

    }

    private function getPulls( $url, $state ){
        $url .= "?state=" . $state;
        
        $request=\Web::instance()->request($url);

        $data = json_decode($request['body']);
        return count($data);
    }

    private function removeParams( $str ){
        return trim(preg_replace('/\s*\{[^)]*\}/', '', $str));
    }
}
