<!DOCTYPE html>
<html lang="en">

<!-- ================== Section Head ================== -->
<?php require_once("view/sections/admin/head.php")?>

<body>
    <!-- ==================  Verifier Section ================== -->
    <?php require_once("view/sections/admin/verifierSession.php")?>



    <!-- ================== Section Menu Haut ================== -->
    <?php require_once("view/sections/admin/menuHaut.php")?>
    

    <!-- ================== Section Menu Gauche ================== -->
    <?php require_once("view/sections/admin/menuGauche.php")?>
    
    <!-- ================== Section Base Content ================== -->
    <?php require_once("view/sections/admin/baseContent.php")?>
    
		

    <!-- ================== Section Footer ================== -->
    <?php require_once("view/sections/admin/footer.php")?>
   
    <!-- ================== Section script ================== -->
    <?php require_once("view/sections/admin/script.php")?>
	
    <!-- ================== Message Error Or Success ================== -->
	<?php require_once("view/sections/admin/msgErrorOrSuccess.php"); ?>


</body>

</html>