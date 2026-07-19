<?php

namespace App\Enums;

enum TimelineType: string
{
    case Appeal = 'appeal';
    case Background = 'background';
    case Case = 'case';
    case Character = 'character';
    case Charges = 'charges';
    case Confession = 'confession';
    case CrimeScene = 'crime-scene';
    case Custody = 'custody';
    case Custom = 'custom';
    case Documents = 'documents';
    case Evidence = 'evidence';
    case Family = 'family';
    case Forensics = 'forensics';
    case Hearing = 'hearing';
    case Investigation = 'investigation';
    case Media = 'media';
    case Plea = 'plea';
    case Recovery = 'recovery';
    case Relationship = 'relationship';
    case Timeline = 'timeline';
    case Trial = 'Trial';

    public function label(): string
    {
        return match ($this) {

            self::Appeal => 'Appeal',
            self::Background => 'Background',
            self::Case => 'Case',
            self::Character => 'Character',
            self::Charges => 'Charges',
            self::Confession => 'Confession',
            self::CrimeScene => 'CrimeScene',
            self::Custody => 'Custody',
            self::Custom => 'Custom',
            self::Documents => 'Documents',
            self::Evidence => 'Evidence',
            self::Family => 'Family',
            self::Forensics => 'Forensics',
            self::Hearing => 'Hearing',
            self::Investigation => 'Investigation',
            self::Media => 'Media',
            self::Plea => 'Plea',
            self::Recovery => 'Recovery',
            self::Relationship => 'Relationship',
            self::Timeline => 'Timeline',
            self::Trial => 'Trial',
               
        };
    }
}