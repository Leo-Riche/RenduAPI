<?php

namespace App\Enum;

enum ConsultationStatus: string
{
    case PROGRAMMME = 'programmé';
    case EN_COURS = 'en_cours';
    case TERMINE = 'termine';
}

