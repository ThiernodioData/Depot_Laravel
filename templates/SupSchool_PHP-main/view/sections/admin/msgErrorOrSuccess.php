<!-- === CDF SWEET Alert 2 === -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- === Message Error === -->
<?php if (
    isset($_GET["error"]) && $_GET["error"] == 1 &&
    isset($_GET["message"]) && !empty($_GET["message"]) &&
    isset($_GET["title"]) && !empty($_GET["title"])
): ?>
<script>
    Swal.fire({
        title: <?= json_encode($_GET["title"]) ?>,
        text: <?= json_encode($_GET["message"]) ?>,
        icon: "error",
        timerProgressBar: true,
        timer: 3000
    });
</script>
<?php endif; ?>

<!-- === Message Success === -->
<?php if (
    isset($_GET["success"]) && $_GET["success"] == 1 &&
    isset($_GET["message"]) && !empty($_GET["message"]) &&
    isset($_GET["title"]) && !empty($_GET["title"])
): ?>
<script>
    Swal.fire({
        title: <?= json_encode($_GET["title"]) ?>,
        text: <?= json_encode($_GET["message"]) ?>,
        icon: "success",
        timerProgressBar: true,
        timer: 3000
    });
</script>
<?php endif; ?>
