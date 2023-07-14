<?php 
    
    if(!empty($menusTop ) ) { 

        foreach($menusTop as $menuTop) {

            echo html_entity_decode($menuTop['content']); 
        }

    }

    echo html_entity_decode($post[0]["body"]); 
     
    if(!empty($menusBottom ) ) { 

        foreach($menusBottom as $menuBottom) {

            echo html_entity_decode($menuBottom['content']); 
        }

    }

