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
      
       
         if($horaatual->format('H:i:s')<'08:40:00'){
            $intervalo=0;
         }   
         elseif($horaatual->format('H:i:s')<'09:40:00'){
            $intervalo=1;
         }
         elseif($horaatual->format('H:i:s')<'10:40:00'){
            $intervalo=2;
         }
         elseif($horaatual->format('H:i:s')<'11:40:00'){
            $intervalo=3;
         }
         elseif($horaatual->format('H:i:s')<'12:40:00'){
            $intervalo=4;
         }
         elseif($horaatual->format('H:i:s')<'13:40:00'){
            $intervalo=5;
         }
         elseif($horaatual->format('H:i:s')<'14:40:00'){
            $intervalo=6;
         }
         elseif($horaatual->format('H:i:s')<'15:40:00'){
            $intervalo=7;
         }
         elseif($horaatual->format('H:i:s')<'16:40:00'){
            $intervalo=8;
         }
         elseif($horaatual->format('H:i:s')<'17:40:00'){
            $intervalo=9;
         }
         elseif($horaatual->format('H:i:s')>='17:40:00'){
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
         $hora='06:40 - 07:40';
      }  
        if($intervalo==1){
            $hora='07:40 - 08:40';
         }   
         elseif($intervalo==2){
            $hora='08:41 - 09:40';
         } 
         elseif($intervalo==3){
            $hora='09:41 - 10:40';
         } 
         elseif($intervalo==4){
            $hora='10:41 - 11:40';
         }
         elseif($intervalo==5){
            $hora='11:41 - 12:40';
         }  
         elseif($intervalo==6){
            $hora='12:41 - 13:40';
         } 
         elseif($intervalo==7){
            $hora='13:41 - 14:40';
         } 
         elseif($intervalo==8){
            $hora='14:41 - 15:40';
         }  
         elseif($intervalo==9){
            $hora='15:41 - 16:40';
         }
         elseif($intervalo==10){
            $hora='16:41 - 17:40';
         }else{
            $hora='17:41 - 18:00';
         } 
        return $hora;
      }
      public function devolveintervalo($hora)
        {
        
            switch ($hora) {
                case $hora < '07:40:00':
                    return 0;
                    break;
                case $hora < '08:41':
                    return 1;
                    break;
                case $hora < '09:41':
                    return 2;
                    break;
                case $hora < '10:41':
                    return 3;
                    break;
                case $hora < '11:41':
                    return 4;
                    break;
                case $hora < '12:41':
                    return 5;
                    break;
                case $hora < '13:41':
                    return 6;
                    break;
                case $hora < '14:41':
                    return 7;
                    break;
                case $hora < '15:41':
                    return 8;
                    break;
                case $hora < '16:41':
                    return 9;
                    break;
                case $hora < '17:41':
                    return 10;
                    break;
                case $hora < '18:41':
                    return 11;
                    break;
                case $hora >= '18:41':
                    return 12;
                    break;
            }
        }
}
?>