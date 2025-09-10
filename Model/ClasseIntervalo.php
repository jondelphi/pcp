<?php
date_default_timezone_set('America/Sao_Paulo');

class Intervalo
{
    public function QuantosIntervalos()
    {
        $horaatual=new DateTime('now');
        $diaatual=new DateTime('now');
        $diaadm=new DateTime($_SESSION['ADMdiapont']);
        
        if ($diaatual->format('Y-m-d')==$diaadm->format('Y-m-d')) {
      
       
         if($horaatual->format('H:i:s')<'08:30:00'){
            $intervalo=0;
         }   
         elseif($horaatual->format('H:i:s')<'09:30:00'){
            $intervalo=1;
         }
         elseif($horaatual->format('H:i:s')<'10:30:00'){
            $intervalo=2;
         }
         elseif($horaatual->format('H:i:s')<'11:30:00'){
            $intervalo=3;
         }
         elseif($horaatual->format('H:i:s')<'12:30:00'){
            $intervalo=4;
         }
         elseif($horaatual->format('H:i:s')<'13:30:00'){
            $intervalo=5;
         }
         elseif($horaatual->format('H:i:s')<'14:30:00'){
            $intervalo=6;
         }
         elseif($horaatual->format('H:i:s')<'15:30:00'){
            $intervalo=7;
         }
         elseif($horaatual->format('H:i:s')<'16:30:00'){
            $intervalo=8;
         }
         elseif($horaatual->format('H:i:s')<'17:30:00'){
            $intervalo=9;
         }
         elseif($horaatual->format('H:i:s')>='17:30:00'){
            $intervalo=10;
         }
         else{
            $intervalo=8;
         }
            
        }else{
            $intervalo=8;
        }
        return $intervalo;


    }

    public function QualIntervalo($intervalo)
    {
      if($intervalo==0){
         $hora='06:30 - 07:30';
      }  
        if($intervalo==1){
            $hora='07:30 - 08:30';
         }   
         elseif($intervalo==2){
            $hora='08:31 - 09:30';
         } 
         elseif($intervalo==3){
            $hora='09:31 - 10:30';
         } 
         elseif($intervalo==4){
            $hora='10:31 - 11:30';
         }
         elseif($intervalo==5){
            $hora='11:31 - 12:30';
         }  
         elseif($intervalo==6){
            $hora='12:31 - 13:30';
         } 
         elseif($intervalo==7){
            $hora='13:31 - 14:30';
         } 
         elseif($intervalo==8){
            $hora='14:31 - 15:30';
         }  
         elseif($intervalo==9){
            $hora='15:31 - 16:30';
         }
         elseif($intervalo==10){
            $hora='16:31 - 17:30';
         }else{
            $hora='17:31 - 18:00';
         } 
        return $hora;
      }
      public function devolveintervalo($hora)
        {
        
            switch ($hora) {
                case $hora < '07:30:00':
                    return 0;
                    break;
                case $hora < '08:31':
                    return 1;
                    break;
                case $hora < '09:31':
                    return 2;
                    break;
                case $hora < '10:31':
                    return 3;
                    break;
                case $hora < '11:31':
                    return 4;
                    break;
                case $hora < '12:31':
                    return 5;
                    break;
                case $hora < '13:31':
                    return 6;
                    break;
                case $hora < '14:31':
                    return 7;
                    break;
                case $hora < '15:31':
                    return 8;
                    break;
                case $hora < '16:31':
                    return 9;
                    break;
                case $hora < '17:31':
                    return 10;
                    break;
                case $hora < '18:31':
                    return 11;
                    break;
                case $hora >= '18:31':
                    return 12;
                    break;
            }
        }
}
?>