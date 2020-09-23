<?php

namespace Gendiff\data\dataForGendiff;

function getReference()
{
    return <<<DOCOPT
    Generate diff
    
    Usage:
    gendiff (-h|--help)
    gendiff (-v|--version)
    
    Options:
        -h --help                     Show this screen
        -v --version                  Show version
    DOCOPT;
}
