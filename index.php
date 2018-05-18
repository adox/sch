<?php
define('F3_TEMPLATE_DIR', 'template');

require 'vendor/autoload.php';
$f3 = \Base::instance();
$f3->set('AUTOLOAD','autoload/');


$f3->route('GET /',
    function($f3) {
        $f3->reroute("@form");
    }

);

$f3->route('GET @form: /form',
    function($f3) {
        echo \Template::instance()->render( F3_TEMPLATE_DIR . '/form.htm' );
    }
);

$f3->route('POST /form',
    function($f3) {
        $user1 = $f3->get('POST.user1');
        $repo1 = $f3->get('POST.repo1');
        $user2 = $f3->get('POST.user2');
        $repo2 = $f3->get('POST.repo2');

        $f3->reroute("@show(@user1=$user1,@repo1=$repo1,@user2=$user2,@repo2=$repo2)");
    }
);

$f3->route('GET @show: /@user1/@repo1/@user2/@repo2',
    function($f3, $params) {
        $p = new Sch\Projects;

        $p->add(new Sch\Project($params['user1'], $params['repo1']));
        $p->add(new Sch\Project($params['user2'], $params['repo2']));

        $f3->set('head', Sch\Project::expose() );
        $f3->set('data', $p->getProjects() );
        $f3->set('template_dir', F3_TEMPLATE_DIR);

        echo \Template::instance()->render( F3_TEMPLATE_DIR . '/main.htm' );
    }
);

$f3->route('GET @api: /api/get/@user1/@repo1/@user2/@repo2',
    function($f3, $params){

        $p = new Sch\Projects;
        $p->add(new Sch\Project($params['user1'], $params['repo1']));
        $p->add(new Sch\Project($params['user2'], $params['repo2']));

        echo json_encode($p->getProjects());
    }
);

$f3->run();
