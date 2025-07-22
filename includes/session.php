<style>
/*for session alert*/
  .alert {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  /* Common alert style */
  .status_alert, .warning_alert, .danger_alert {
    padding: 5px 70px 5px 50px;
    border-radius: 8px;
    position: relative;
    font-family: Arial, sans-serif;
    font-size: 16px;
    width: fit-content;
    min-width: 450px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }

  /* Different alert types */
  .status_alert {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
  }

  .warning_alert {
    background-color: #fff3cd;
    color: #664d03;
    border: 1px solid #ffecb5;
  }

  .danger_alert {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
  }

  /* Close button style */
  .btn-close {
    position: absolute;
    top: 20px;
    right: 10px;
    background: none;
    border: none;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
    color: inherit;
  }

    @media(max-width:570px){
    .status_alert, .warning_alert, .danger_alert {
      padding: 1px 7px 1px 10px;
      font-size: 11px;
      min-width: 270px;
    }
     .btn-close {
      position: absolute;
      top: 10px;
      right: 8px;
      font-size: 16px;
    }
  }
  
</style>

<?php if (isset($_SESSION['status'])) : ?>
    <div class="alert">
      <div class="status_alert" role="alert">
          <h3>
          <strong>Hey!</strong> <?= $_SESSION['status']; ?>
          <button type="button" class="btn-close">&times;</button>
          </h3>
      </div>
  </div>
<?php 
    unset($_SESSION['status']);
endif; ?>

<?php if(isset($_SESSION['success'])) : ?>
    <div class="alert">
      <div class="status_alert" role="alert">
          <h3>
          <strong>Hey!</strong> <?= $_SESSION['success']; ?>
          <button type="button" class="btn-close">&times;</button>
          </h3>
      </div>
    </div>
<?php 
  unset($_SESSION['success']);
endif;
?>

<?php if(isset($_SESSION['warning'])) : ?>
    <div class="alert">
      <div class="warning_alert" role="alert">
          <h3>
          <strong>Hey!</strong> <?= $_SESSION['warning']; ?>
          <button type="button" class="btn-close">&times;</button>
          </h3>
      </div>
    </div>
<?php 
  unset($_SESSION['warning']);
endif;
?>

<?php if(isset($_SESSION['danger'])) : ?>
    <div class="alert">
      <div class="danger_alert" role="alert">
          <h3>
          <strong>Hey!</strong> <?= $_SESSION['danger']; ?>
          <button type="button" class="btn-close">&times;</button>
          </h3>
      </div>
    </div>
<?php 
  unset($_SESSION['danger']);
endif;
?>

<script>
// সকল close button select করি
const closeButtons = document.querySelectorAll('.btn-close');

closeButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const alertBox = button.closest('.alert');
        if (alertBox) {
            alertBox.style.opacity = '0';
            alertBox.style.transition = 'opacity 0.5s ease';

            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 500);
        }
    });
});
</script>
