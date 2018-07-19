<?php

namespace LogicBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class DatePartFunction extends FunctionNode {

    public $parteFecha;
    public $fecha;

    public function getSql(SqlWalker $sqlWalker) {
        return "date_part( "
                . $this->parteFecha->dispatch($sqlWalker)
                . " , "
                . $this->fecha->dispatch($sqlWalker)
                . ")";
    }

    public function parse(Parser $parser) {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);       //Nombre de la Funcion
        $parser->match(Lexer::T_OPEN_PARENTHESIS); //Parantesis abierto
        $this->parteFecha = $parser->StringPrimary();//Parte de la fecha
        $parser->match(Lexer::T_COMMA);
        $this->fecha = $parser->StringPrimary(); // Fecha

        $parser->match(Lexer::T_CLOSE_PARENTHESIS); //Parentesis Cerrado
    }

}
