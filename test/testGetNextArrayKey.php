<!DOCTYPE html>
<html>
<body>
<pre>

<?php

/* There are many built in functions to process arrays.  There seems to be no function
	to find the next element in the array given a key.  These functions, getNext()
	and getPrevious() were found here:
	
	https://stackoverflow.com/a/4133495/10723550
 */

function getNext(&$array, $curr_key)
{
    $next = 0;
    reset($array);

    do
    {
        $tmp_key = key($array);
        $res = next($array);
    } while ( ($tmp_key != $curr_key) && $res );

    if( !$res )
	{
		reset($array) ;
	}
    $next = key($array);

    return $next;
}

function getPrev(&$array, $curr_key)
{
    end($array);
    $prev = key($array);

    do
    {
        $tmp_key = key($array);
        $res = prev($array);
    } while ( ($tmp_key != $curr_key) && $res );

    if( !$res )
    {
		end($array);
    }
    $prev = key($array);

    return $prev;
}



$car = array("brand"=>"Ford", "model"=>"Mustang", "year"=>1964);
var_dump($car);

echo 'If I choose key "model"...' . $car["model"] . '<br/>' ;
$key = getNext($car, "model");
echo '...the next array reference is: ' . $key . '<br/>';
$nextValue = $car[$key] ;
echo '... whose value is: ' . $nextValue . '<br/>';

echo 'Now trying the end loop from $car["year"]. <br/>' ;
$key = getNext($car, "year");
echo '...the next array reference is: ' . $key . '<br/>';
$nextValue = $car[$key] ;
echo '... whose value is: ' . $nextValue . '<br/>';

echo 'And now trying the previous key from $car["brand"]. <br/>' ;
$key = getPrev($car, $key);
echo '...the previous array reference is: ' . $key . '<br/>';
$nextValue = $car[$key] ;
echo '... whose value is: ' . $nextValue . '<br/>';


?>

</pre>
</body>
</html>
