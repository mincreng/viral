<?php
    if (!defined('ROOT')) {
        exit;
    }

    return array(
        'name' => 'pages',
        'primary' => 'prefix',
        'data' => array(
            array('type'=>1,'prefix'=>'home','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'login','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'recover','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'register','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'panel','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'logout','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'contact','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'account','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'rss','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'upcoming','title'=>'','content'=>'','options'=>''),
            array('type'=>1,'prefix'=>'random_post','title'=>'','content'=>'','options'=>''),
            array('type'=>2,'prefix'=>'privacy_policy','title'=>'Privacy Policy','content'=>'<p><strong>Your Privacy Policy Here</strong></p>','options'=>''),
            array('type'=>2,'prefix'=>'terms_conditions','title'=>'Terms &amp; Conditions','content'=>'<p>Your Terms &amp; Conditions Here</p>','options'=>''),
            array('type'=>2,'prefix'=>'about_us','title'=>'About Us','content'=>'<p>About Us</p>','options'=>'')
        )
    );
