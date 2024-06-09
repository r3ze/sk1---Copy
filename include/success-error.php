   <style>
       .error {
           display: flex;
           color: #d82929;
           /*  Error messages in a clear red */
           background-color: #ffe8e8;
           border: 1px solid #d82929;
           padding: 8px 12px;
           margin-bottom: 15px;
           border-radius: 4px;
           justify-content: center !important;
       }

       .success-message {
           display: flex;
           color: #28a745;
           background-color: #ccf5d0;
           border: 1px solid #28a745;
           padding: 8px 12px;
           /* margin-bottom: 15px; */
           border-radius: 4px;
           justify-content: center !important;
       }

       .success-message p,
       .error p {
           margin-bottom: 0px;
       }
   </style>

   <?php if (isset($_GET['success'])) : ?>
       <div class="success-message">
           <p><?php echo $_GET['success']; ?></p>
       </div>
   <?php elseif (isset($_GET['error'])) : ?>
       <div class="error">
           <p><?php echo $_GET['error']; ?></p>
       </div>
   <?php endif; ?>