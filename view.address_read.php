<!--
<ul class='breadcrumb'><li><a href='./'>Home</a><span class='divider'>/</span></li><li class='active'>My Account</li></ul>
<h1>My Account</h1>
-->
<?php
echo "<div class='address'>";
echo "<table>";
echo "<tr><td>".$address->recipient."</td></tr>";
echo "<tr><td>".$address->street."</td></tr>";
echo "<tr><td>".$address->city."</td></tr>";
echo "<tr><td>".$address->region."</td></tr>";
echo "<tr><td>".$address->country."</td></tr>";
echo "<tr><td>".$address->postal."</td></tr>";
echo "<tr><td>".$address->phone."</td></tr>";
echo "</table>";
echo "</div>";
?>