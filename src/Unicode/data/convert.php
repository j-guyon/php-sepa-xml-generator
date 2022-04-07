<?php

require '../../sapphire/dev/Parser.php' ;

class QuoteParser extends Parser
{
    /* ws: /[\s]* /x */
    public function match_ws()
    {
        $result = ["name" => "ws", "text" => ""];
        if (($subres = $this->rx('/[\s]* /x')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* char: /\\\\./ */
    public function match_char()
    {
        $result = ["name" => "char", "text" => ""];
        if (($subres = $this->rx('/\\\\./')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* hex: /\\\\x[A-Fa-f0-9]+/ */
    public function match_hex()
    {
        $result = ["name" => "hex", "text" => ""];
        if (($subres = $this->rx('/\\\\x[A-Fa-f0-9]+/')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* escaped: hex / char */
    public function match_escaped()
    {
        $result = $this->construct("escaped");
        $procflag = false ;
        $_6 = null;
        do {
            $res_3 = $result;
            $pos_3 = $this->pos;
            $key = "hex:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_hex()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
                $_6 = true;
                break;
            }
            $result = $res_3;
            $this->pos = $pos_3;
            $key = "char:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_char()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
                $_6 = true;
                break;
            }
            $result = $res_3;
            $this->pos = $pos_3;
            $_6 = false;
            break;
        } while (0);
        if ($_6 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_6 === false) {
            return false;
        }
    }

    /* not-curly: /[^}]/ */
    public function match_not_curly()
    {
        $result = ["name" => "not_curly", "text" => ""];
        if (($subres = $this->rx('/[^}]/')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* not-double: /[^"]/ */
    public function match_not_double()
    {
        $result = ["name" => "not_double", "text" => ""];
        if (($subres = $this->rx('/[^"]/')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* not-single: /[^\']/ */
    public function match_not_single()
    {
        $result = ["name" => "not_single", "text" => ""];
        if (($subres = $this->rx('/[^\']/')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* qquotestr: ( escaped / not-curly )* */
    public function match_qquotestr()
    {
        $result = $this->construct("qquotestr");
        $procflag = false ;
        while (true) {
            $res_17 = $result;
            $pos_17 = $this->pos;
            $_16 = null;
            do {
                $_14 = null;
                do {
                    $res_11 = $result;
                    $pos_11 = $this->pos;
                    $key = "escaped:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_escaped()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                        $_14 = true;
                        break;
                    }
                    $result = $res_11;
                    $this->pos = $pos_11;
                    $key = "not_curly:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_not_curly()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                        $_14 = true;
                        break;
                    }
                    $result = $res_11;
                    $this->pos = $pos_11;
                    $_14 = false;
                    break;
                } while (0);
                if ($_14 === false) {
                    $_16 = false;
                    break;
                }
                $_16 = true;
                break;
            } while (0);
            if ($_16 === false) {
                $result = $res_17;
                $this->pos = $pos_17;
                unset($res_17);
                unset($pos_17);
                break;
            }
        }
        if ($procflag) {
            unset($result["nodes"]) ;
        }
        return $result ;
    }

    /* qquoted: "qq{" qquotestr "}" */
    public function match_qquoted()
    {
        $result = $this->construct("qquoted");
        $procflag = false ;
        $_21 = null;
        do {
            if (($subres = $this->literal("qq{")) !== false) {
                $result["text"] .= $subres;
            } else {
                $_21 = false;
                break;
            }
            $key = "qquotestr:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_qquotestr()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_21 = false;
                break;
            }
            if (($subres = $this->literal("}")) !== false) {
                $result["text"] .= $subres;
            } else {
                $_21 = false;
                break;
            }
            $_21 = true;
            break;
        } while (0);
        if ($_21 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_21 === false) {
            return false;
        }
    }

    public function qquoted_qquotestr(&$self, $sub)
    {
        $self['string'] = str_replace('"', '\"', $sub['text']) ;
    }

    /* dquotestr: ( escaped / not-double )* */
    public function match_dquotestr()
    {
        $result = $this->construct("dquotestr");
        $procflag = false ;
        while (true) {
            $res_29 = $result;
            $pos_29 = $this->pos;
            $_28 = null;
            do {
                $_26 = null;
                do {
                    $res_23 = $result;
                    $pos_23 = $this->pos;
                    $key = "escaped:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_escaped()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                        $_26 = true;
                        break;
                    }
                    $result = $res_23;
                    $this->pos = $pos_23;
                    $key = "not_double:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_not_double()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                        $_26 = true;
                        break;
                    }
                    $result = $res_23;
                    $this->pos = $pos_23;
                    $_26 = false;
                    break;
                } while (0);
                if ($_26 === false) {
                    $_28 = false;
                    break;
                }
                $_28 = true;
                break;
            } while (0);
            if ($_28 === false) {
                $result = $res_29;
                $this->pos = $pos_29;
                unset($res_29);
                unset($pos_29);
                break;
            }
        }
        if ($procflag) {
            unset($result["nodes"]) ;
        }
        return $result ;
    }

    /* dquoted: '"' dquotestr '"' */
    public function match_dquoted()
    {
        $result = $this->construct("dquoted");
        $procflag = false ;
        $_33 = null;
        do {
            if (($subres = $this->literal('"')) !== false) {
                $result["text"] .= $subres;
            } else {
                $_33 = false;
                break;
            }
            $key = "dquotestr:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_dquotestr()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_33 = false;
                break;
            }
            if (($subres = $this->literal('"')) !== false) {
                $result["text"] .= $subres;
            } else {
                $_33 = false;
                break;
            }
            $_33 = true;
            break;
        } while (0);
        if ($_33 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_33 === false) {
            return false;
        }
    }

    public function dquoted_dquotestr(&$self, $sub)
    {
        $self['string'] = $sub['text'] ;
    }

    /* squotestr: ( escaped / not-single )* */
    public function match_squotestr()
    {
        $result = $this->construct("squotestr");
        $procflag = false ;
        while (true) {
            $res_41 = $result;
            $pos_41 = $this->pos;
            $_40 = null;
            do {
                $_38 = null;
                do {
                    $res_35 = $result;
                    $pos_35 = $this->pos;
                    $key = "escaped:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_escaped()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                        $_38 = true;
                        break;
                    }
                    $result = $res_35;
                    $this->pos = $pos_35;
                    $key = "not_single:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_not_single()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                        $_38 = true;
                        break;
                    }
                    $result = $res_35;
                    $this->pos = $pos_35;
                    $_38 = false;
                    break;
                } while (0);
                if ($_38 === false) {
                    $_40 = false;
                    break;
                }
                $_40 = true;
                break;
            } while (0);
            if ($_40 === false) {
                $result = $res_41;
                $this->pos = $pos_41;
                unset($res_41);
                unset($pos_41);
                break;
            }
        }
        if ($procflag) {
            unset($result["nodes"]) ;
        }
        return $result ;
    }

    /* squoted: "'" squotestr "'" */
    public function match_squoted()
    {
        $result = $this->construct("squoted");
        $procflag = false ;
        $_45 = null;
        do {
            if (($subres = $this->literal("'")) !== false) {
                $result["text"] .= $subres;
            } else {
                $_45 = false;
                break;
            }
            $key = "squotestr:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_squotestr()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_45 = false;
                break;
            }
            if (($subres = $this->literal("'")) !== false) {
                $result["text"] .= $subres;
            } else {
                $_45 = false;
                break;
            }
            $_45 = true;
            break;
        } while (0);
        if ($_45 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_45 === false) {
            return false;
        }
    }

    public function squoted_squotestr(&$self, $sub)
    {
        $self['string'] = $sub['text'] ;
    }

    /* member: qquoted / dquoted / squoted */
    public function match_member()
    {
        $result = $this->construct("member");
        $procflag = false ;
        $_54 = null;
        do {
            $res_47 = $result;
            $pos_47 = $this->pos;
            $key = "qquoted:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_qquoted()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
                $_54 = true;
                break;
            }
            $result = $res_47;
            $this->pos = $pos_47;
            $_52 = null;
            do {
                $res_49 = $result;
                $pos_49 = $this->pos;
                $key = "dquoted:{$this->pos}";
                $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_dquoted()));
                if ($subres !== false) {
                    $procflag = $this->store($result, $subres) || $procflag;
                    $_52 = true;
                    break;
                }
                $result = $res_49;
                $this->pos = $pos_49;
                $key = "squoted:{$this->pos}";
                $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_squoted()));
                if ($subres !== false) {
                    $procflag = $this->store($result, $subres) || $procflag;
                    $_52 = true;
                    break;
                }
                $result = $res_49;
                $this->pos = $pos_49;
                $_52 = false;
                break;
            } while (0);
            if ($_52 === true) {
                $_54 = true;
                break;
            }
            $result = $res_47;
            $this->pos = $pos_47;
            $_54 = false;
            break;
        } while (0);
        if ($_54 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_54 === false) {
            return false;
        }
    }

    /* array: "[" ws member ws ( "," ws member ws )* */
    public function match_array()
    {
        $result = $this->construct("array");
        $procflag = false ;
        $_66 = null;
        do {
            if (($subres = $this->literal("[")) !== false) {
                $result["text"] .= $subres;
            } else {
                $_66 = false;
                break;
            }
            $key = "ws:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_ws()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_66 = false;
                break;
            }
            $key = "member:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_member()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_66 = false;
                break;
            }
            $key = "ws:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_ws()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_66 = false;
                break;
            }
            while (true) {
                $res_65 = $result;
                $pos_65 = $this->pos;
                $_64 = null;
                do {
                    if (($subres = $this->literal(",")) !== false) {
                        $result["text"] .= $subres;
                    } else {
                        $_64 = false;
                        break;
                    }
                    $key = "ws:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_ws()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                    } else {
                        $_64 = false;
                        break;
                    }
                    $key = "member:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_member()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                    } else {
                        $_64 = false;
                        break;
                    }
                    $key = "ws:{$this->pos}";
                    $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_ws()));
                    if ($subres !== false) {
                        $procflag = $this->store($result, $subres) || $procflag;
                    } else {
                        $_64 = false;
                        break;
                    }
                    $_64 = true;
                    break;
                } while (0);
                if ($_64 === false) {
                    $result = $res_65;
                    $this->pos = $pos_65;
                    unset($res_65);
                    unset($pos_65);
                    break;
                }
            }
            $_66 = true;
            break;
        } while (0);
        if ($_66 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_66 === false) {
            return false;
        }
    }

    public function array___construct(&$self)
    {
        $self['strings'] = [] ;
    }

    public function array_member(&$self, $sub)
    {
        $self['strings'][] = $sub['nodes'][0]['string'] ;
    }

    /* not-square: /[^\[]+/ */
    public function match_not_square()
    {
        $result = ["name" => "not_square", "text" => ""];
        if (($subres = $this->rx('/[^\[]+/')) !== false) {
            $result["text"] .= $subres;
            return $result;
        } else {
            return false;
        }
    }

    /* start_garbage: not-square "[" not-square */
    public function match_start_garbage()
    {
        $result = $this->construct("start_garbage");
        $procflag = false ;
        $_72 = null;
        do {
            $key = "not_square:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_not_square()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_72 = false;
                break;
            }
            if (($subres = $this->literal("[")) !== false) {
                $result["text"] .= $subres;
            } else {
                $_72 = false;
                break;
            }
            $key = "not_square:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_not_square()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_72 = false;
                break;
            }
            $_72 = true;
            break;
        } while (0);
        if ($_72 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_72 === false) {
            return false;
        }
    }

    /* definition: start_garbage array */
    public function match_definition()
    {
        $result = $this->construct("definition");
        $procflag = false ;
        $_76 = null;
        do {
            $key = "start_garbage:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_start_garbage()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_76 = false;
                break;
            }
            $key = "array:{$this->pos}";
            $subres = ($this->packhas($key) ? $this->packread($key) : $this->packwrite($key, $this->match_array()));
            if ($subres !== false) {
                $procflag = $this->store($result, $subres) || $procflag;
            } else {
                $_76 = false;
                break;
            }
            $_76 = true;
            break;
        } while (0);
        if ($_76 === true) {
            if ($procflag) {
                unset($result["nodes"]) ;
            }
            return $result ;
        }
        if ($_76 === false) {
            return false;
        }
    }

    public function definition_array(&$self, $sub)
    {
        $self['array'] = $sub ;
    }
}

foreach (glob('perl_source/*.pm') as $fname) {
    preg_match('!perl_source/(.*)\.pm!', $fname, $mtch) ;
    $c = $mtch[1] ;
    print "$c\n" ;

    $string = file_get_contents($fname) ;
    $p = new QuoteParser($string) ;
    $r = $p->match_definition() ;

    $out = "<?php \n" . '\SEPA\Unicode\Unidecode::$tr[0' . $c . '] = array( "' . implode('","', $r['array']['strings']) . '" );' ;
    // file_put_contents( $c . '.php', $out ) ;
}
