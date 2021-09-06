<?php


namespace App\Service;


use App\Entity\Participant;

class GenerateToken
{
public function getToken(Participant $participant): string{
    $val=  substr($participant->getNom(),-3);
    $val.= substr($participant->getPrenom(),-3);
    $val.= substr($participant->getEmail(),-5);
    $val.= substr($participant->getPassword(),7);
    $date= new \DateTime();
    $val.= $date->format('Y-m-d H:i:s');
    return md5($val);
}
}
