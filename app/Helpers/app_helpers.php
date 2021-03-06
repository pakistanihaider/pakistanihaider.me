<?php
if(!function_exists('percentageColor')){
    function percentageColor($percentage,$type='progressbar'){
        if(empty($percentage))
            return '000000';

        if ($type || $type == 'progressBar') {
            $colors = [
                'excellent' => 'green-bar',
                'veryGood' => 'green-bar',
                'good' => 'blue-bar',
                'average' => 'yellow-bar',
                'starter' => 'red-bar'
            ];
        } elseif ($type === 'label') {
            $colors = [
                'excellent' => 'success',
                'veryGood' => 'success',
                'good' => 'primary',
                'average' => 'warning',
                'starter' => '#danger'
            ];
        } else {
            $colors = [
                'excellent' => '#01660b',
                'veryGood' => '#01c853',
                'good' => '#2879ff',
                'average' => '#fec107',
                'starter' => '#ff3d00'
            ];
        }

        if($percentage>=0 && $percentage<30){
            return $colors['starter'];
        }else if($percentage>=30 && $percentage<60){
            return $colors['average'];
        }else if($percentage>=60 && $percentage<75){
            return $colors['good'];
        }else if($percentage>=75 && $percentage<90){
            return $colors['veryGood'];
        }else if($percentage>=90){
            return $colors['excellent'];
        }
    }
}