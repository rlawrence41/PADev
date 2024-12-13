<?php
/**
 * We just want to hash our password using the current DEFAULT algorithm.
 * This is presently BCRYPT, and will produce a 60 character result.
 *
 * Beware that DEFAULT may change over time, so you would want to prepare
 * By allowing your storage to expand past 60 characters (255 would be good)
 
 * NOTE: Refreshing this page causes the hashes to be regenerated!
 * How is this going to work for authentication?
 
 */
echo "<h1>Test <i>password_hash()</i> Function</h1>";

echo "<h2>PASSWORD_DEFAULT</h2>";
$eol = "\n<br/>";

$hash = password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
echo $hash . $eol;
echo password_hash("I'mLookingOver", PASSWORD_DEFAULT);
echo $eol;
echo password_hash("a4LeafClover", PASSWORD_DEFAULT);
echo $eol;
echo password_hash("thatIoverlookedBefore", PASSWORD_DEFAULT);


echo "<h2>PASSWORD_BCRYPT</h2>";
echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT);
echo $eol;
echo password_hash("I'mLookingOver", PASSWORD_BCRYPT);
echo $eol;
echo password_hash("a4LeafClover", PASSWORD_BCRYPT);
echo $eol;
echo password_hash("thatIoverlookedBefore", PASSWORD_BCRYPT);


echo "<h2>Test the password_verify() function</h2>";

// See the password_hash() example to see where this came from.
#$hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
#$hash = '$2y$10$UnzdKfeJrwI0Fs5/f2Jkiedl2ZfaS9UI8rbIjxscxZes5HUD/LAKW';
#$hash = '$2y$10$e6jYH161uK4CLnBmytG/6.4M6CI6G61BOSi47I2EJqIBn4IA6VG/m';
#$hash = '$2y$10$a9XX5WQT5md1/atEyR2cCOQB2UZGARTSRJNOpXfbTe/gRKg1sXdDW';

//  The following hash was captured for the second password value above.  
//	It should fail!
$hash = '$2y$10$W7I6PNikmIdWMNFcBHrieuGvPDC3WZKr7XQ8LTA1FfJ9E2PAKmF5m';

echo $eol . "Wrong password:  ";
if (password_verify('rasmuslerdorf', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}


// This SHOULD work though...
echo $eol . "Right password:  ";
if (password_verify("I'mLookingOver", $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}

// Another that should work...
$hash = '$2y$10$x0q95y2N5ufBlJ1l8DWjOOKiQyTBsm/MdtXDWDe4LeRlHzQo1uHbG';
echo $eol . "Another password:  ";
if (password_verify('harmonylaw41', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}


echo $eol;
echo "<h2>test password_verify() w/ it's own hash...</h2>";

// What happens if I submit the hashed value instead...

if (password_verify($hash, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>