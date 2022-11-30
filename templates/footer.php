<?php

// Si la page est appelée directement par son adresse, on redirige en passant par la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:../index.php");
  die("");
}

?>

<!-- Fin du corps de page -->

<!--
<div class="clearFix"></div>

</div>
</div>

<div id="footer">
  <div class="container">
   	 <p class="text-muted credit"></p>
  </div>
</div>
-->
</body>
</html>
