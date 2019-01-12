    </main>

    <footer class="footer bg-light border-top border-primary">
      <div class="container">
        <span class="text-dark"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED'));?>/<?php echo $this->benchmark->memory_usage() ?></span>
      </div>
    </footer>

    <script src="/asset/js/jquery-3.3.1.min.js"></script>
    <script src="/asset/js/popper.min.js"></script>
    <script src="/asset/js/bootstrap.min.js"></script>
    <script src="/src/js" type="text/javascript"></script>
    <!-- <script src="/asset/js/app.js"></script> -->
  </body>
</html>
