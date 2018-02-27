<?php
/*
 You have 2 buttons:
    1. throw - at the click of the button the script runs by generating a number between 1 and 6 using the row function ().
    2. Ready - when pressing the button, the form is processed with the existing numbers

Above each input text field (number) there is a check box,
which if checked ticks that field for the next push of the 'throw' button.

The form's logic follows the algorithm:
- $ arr is the array in which we put the extracted numbers;
- we use the array_count_values ​​($ arr) function;

case 1:
the 5 numbers are all different
The answer is: CACIALMA or QUINTA MICA (1,2,3,4,5,) or QUINTA MARE (2,3,4,5,6)
case 2:
we have 2 equal numbers and 3 different numbers
1 + 1 + 1 + 2 => the answer is: for example if we have $ arr = [1,1,2,3,4] => $ answer = '1 PAIR at 1'
case 3:
we have 4 equal numbers two by two and one different number
1 + 2 + 2 => the answer is: for example if we have $ arr = [1,1,6,6,3] => $ answer = '2 PAIRS AT 1 AND 6 WITH 3 IN THE QUEUE'
case 4:
we have 3 equal numbers and 2 different numbers
1 + 1 + 3 => the answer is: for example if we have $ arr = [2,2,2,1,3] => $ answer = '3 PIECES TO 2 WITH 1 AND 3 IN THE BACK'
Case 5:
we have 3 equal numbers and 2 equal numbers
2 + 3 => the answer is: for example if we have $ arr = [3,3,3,4,4] => $ answer = 'FULL TO 3 WITH 4 IN THE QUEUE'
case 6:
we have 4 equal numbers
1 + 4 => the answer is: for example if we have $ arr = [3,3,3,3,4] => $ answer = ' CARE TO 3 WITH 4 IN THE QUEUE'
Case 7:
we have 5 equal numbers
5 => the answer is: for example if we have $ arr = [3,3,3,3,3] => $ answer = 'YAMS AT 3'
*/




/**
 * functia returneaza true in conditia in care sirul este format din iterarea lui 1 (ex:1,2,3,4,5,...) sau fals daca iterarea nu se face cu 1
 * @param $array
 * @return bool
 */
function isIteration($array)
{
    $sort = true;
    for($i=0;$i<count($array)-1;$i++) {
        if (($array[$i]+1) != ($array[$i + 1])) {
            $sort = false;
            break;
        }
    }
    return $sort;
}


// constructie formular
$html = '<form method="post", action="">'."\n";

$html .= "<table border=\"1\">\n";

// contructie checkboxuri
$html .= "<tr>\n";
for ($i=1;$i<=5;$i++){
    $html .= '<td>
     <input style="width:40px;height:40px;text-align:center;" type="checkbox" name="nr_block'.$i.'" value="'.$i.'"';

    if (isset($_POST['nr_block'.$i])) {
        $html .= ' checked';
    }

    $html .= ' /></td>'."\n";
}
$html .= "</tr>\n";

// contructie campuri text(aici sunt scrise numerele generate aleator)
$html .= "<tr>\n";
for ($i=1;$i<=5;$i++){

    if (isset($_POST['nr'.$i]) && isset($_POST['nr_block'.$i])){
        $val = (int)$_POST['nr'.$i];
    } else {
        $val = rand(1,6);
    }

    $html .= '<td>
     <input readonly style="width:40px;height:40px;text-align:center;font-size: 26px" type="text" name="nr'.$i.'" value="'.$val.'"/>
     </td>'."\n";
}

$html .= "</tr>\n";
$html .= "</table>\n";
$html .= "<input type=\"submit\" value=\"arunca\" name=\"arunca\" />\n";
$html .= "<input type=\"submit\" value=\"gata\" name=\"gata\" />\n";

$html .= '</form>';



// logica formularului
if (isset($_POST['gata'])){

    // preluare numere din $_POST
    for ($i=1;$i<=5;$i++){

        $arr[] = (int) $_POST['nr'.$i];

    }

    $val_count = array_count_values($arr); // aici avem de cate ori au fost extrase anumite numere

    //var_dump( $val_count);exit;

    /*
        discutie dupa array_count_values($arr):
            1+1+1+1+1+1 => avem CACIALMA sau QUINTA MICA(1,2,3,4,5,) sau QUINTA MARE(2,3,4,5,6)
            1+1+1+2     => avem 1 pereche. exemplu daca avem (1,1,2,3,4) afisam 1 PERECHE la 1
            1+2+2       => avem 2 perechi exemplu daca avem (1,1,6,6,3) afisam 2 PERECHI la 1 si 6 cu 3 in coada
            1+1+3       => avem 3 bucati exemplu daca avem (2,2,2,1,3) afisam 3 BUCATI la 2 cu 1 si 3 in coada
            2+3         => avem full exemplu daca avem (3,3,3,4,4) afisam FULL la 3 cu 4 in coada
            1+4         => avem care exemplu daca avem (3,3,3,3,4) CARE la 3
            5           => avem yams exemplu daca avem (3,3,3,3,3) YAMS la 3
    */


    /*
        pentru cazul in care avem 5==count($val_count) mai intai sortam arrayul cu ajutorul functiei predefinite sort()
        iar apoi am pus conditii pt situatia in care arrayul incepe cu cifra 1 sa verifice daca functia definita de mine isIteration() este true(QUINTA MICA)
        sau fals(CACIALMA), sau daca arrayul incepe cu cifra 2 sa verifice daca functia definita de mine isIteration() este true(QUINTA MARE)
        sau fals(CACIALMA)
    */
    if (5==count($val_count)) {  // nu avem nici o pereche, toate cele 5 numere sunt distincte
        sort($arr);
        if ($arr[0]==1) {  // putem avea varianta in care arrayul incepe cu 1
            if (isIteration($arr)) {   // verificam daca functia isIteration() returneaza true
                echo "aveti QUINTA MICA";
            } else {
                echo "aveti CACIALMA";
            }
        }elseif ($arr[0]==2) {   // putem avea varianta in care arrayul incepe cu 2
            if (isIteration($arr)) {    // verificam daca functia isIteration() returneaza true
                echo "aveti QUINTA MARE";
            } else {
                echo "aveti CACIALMA";
            }
        }
    // exemplu tratare caz in care avem 4==count($val_count)
    } elseif (4==count($val_count)) { // putem avea o pereche
        if (in_array(2, $val_count)){  // avem o pereche
            $nr2buc = array_keys($val_count,2);  // este un array cu un element, care la cheia 0 are numarul pentru care avem 2 bucati
            $nr1buc = array_keys($val_count,1); // este un array cu 3 elemente, care la cheile 0,1,2 are numerele distincte
            echo "aveti o pereche la $nr2buc[0]";

        }
    // exemplu tratare caz in care avem 3==count($val_count)
    } elseif (3==count($val_count)) { // putem avea 3 bucati sau 2 perechi
        if (in_array(3,$val_count)) { // avem trei bucati
            // cautam la ce numar avem 3 bucati
            $nr3buc = array_keys($val_count, 3); // este un array cu un element, care la cheia 0 are numarul pentru care avem 3 bucati
            // cautam celelalte 2 numere
            $nr1buc = array_keys($val_count, 1); // este un array cu 2 elemente, care la cheile 0,1 are numarele distincte
            //var_dump($nr3buc,$nr1buc);

            echo "aveti trei bucati de $nr3buc[0] cu $nr1buc[0] si $nr1buc[1] in coada";

        } else { // avem 2 perechi
            // cautam care sunt perechile
            $nr2buc = array_keys($val_count, 2); // este un array cu 2 elemente, care la cheile 0,1 are numerele la care avem perechile
            // cautam numarul singur
            $nr1buc = array_keys($val_count, 1); // este un array cu un element, care la cheia 0 are numarul singular

            echo "aveti 2 perechi de $nr2buc[0] si $nr2buc[1] cu $nr1buc[0] in coada";

        }
    // exemplu tratare caz in care avem 2==count($val_count)
    } elseif (2==count($val_count)) {  // putem avea 4 bucati sau 3 bucati cu o pereche
        if (in_array(3, $val_count)) { // avem 3 bucati cu o pereche
            $nr3buc = array_keys($val_count, 3); // este un array cu un element, care la cheia 0 are numarul pentru care avem 3 bucati
            $nr2buc = array_keys($val_count, 2); // este un array cu un element, care la cheia 0 are numarul pentru care avem 2 bucati

            echo "aveti FULL la $nr3buc[0] cu $nr2buc[0] in coada";

        } else {  // avem 4 bucati
            $nr4buc = array_keys($val_count, 4); // este un array cu un element, care la cheia 0 are numarul pentru care avem 4 bucati
            $nr1buc = array_keys($val_count, 1); // este un array cu un element, care la cheia 0 are numarul pentru care avem 1 bucata

            echo "aveti CARE la $nr4buc[0] cu $nr1buc[0] in coada";

        }
    // exemplu tratare caz in care avem 1==count($val_count)
    } elseif (1==count($val_count)) {   // putem avea 5 bucati
        if (in_array(5, $val_count)) { // avem 5 bucati
            $nr5buc = array_keys($val_count); // este un array cu un element, care la cheia 0 are numarul pentru care avem 5 bucati

            echo "aveti YAMS la $nr5buc[0]";

        }
    }

}

echo $html;



