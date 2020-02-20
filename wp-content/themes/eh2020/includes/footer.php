</div><!-- #content -->

<footer id="colophon" class="site-footer">
  <div class="container site-info text-left p-4">
    <div class="row my-4">
      <div class="col-md-2 d-none d-md-block"></div>
      <div class="col-3 col-md-2">
        <img style="max-width: 120px; width: 100%;" src="https://intothewild.sg/wp-content/themes/ink-child/assets/img/FooterLogo.png" alt="WWF Singapore">
      </div>
      <div class="col-9 col-md-6">
        <div>
          <h3 class="wwf-mission mt-2 mt-md-3"><?= get_field('wwf-mission') ?></h3>
          <p class="mb-2"><?= get_field('footer_line_2') ?></p>
        </div>
        <div class="footer-nav mr-auto mt-2 mt-lg-0">
          <?php if (have_rows('plastic_diet_footer_menu')) : ?>
            <?php while (have_rows('plastic_diet_footer_menu')) : the_row(); ?>
              <a class="nav-link d-inline pl-0" href="<?php echo the_sub_field('nav_url') ?>"><?php echo the_sub_field('nav_label') ?></a>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<!-- Modal -->
<div class="modal fade" id="country_modal" tabindex="-1" role="dialog" aria-labelledby="country_modal_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="country_modal_label">Country Selector</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php  /* menu */
        wp_nav_menu(
          array(
            'menu'              => 'country-menu',
            'theme_location'    => 'country-menu',
            'depth'             => 2,
            'container'         => 'div',
            'container_class'   => '',
            'menu_class'        => 'nav navbar-nav  ',
            'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
            // 'walker'            => new wp_bootstrap_navwalker()
          )
        );
        ?>
      </div>
    </div>
  </div>
</div>
<script>
  var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php wp_footer(); ?>
<script>
  let videoElem = document.getElementsByTagName("video");
  let videoBtn = document.getElementsByClassName(("wp-block-video"));
  for (let video of videoElem) {
    video.addEventListener("click", function(e) {
      e.stopPropagation()
      handlePlayButton(this);
    }, true);
    video.parentElement.addEventListener("click", function(e) {
      e.stopPropagation()
      handlePlayButton(video);
    }, true);
    video.onpause = function(e) {
      e.stopPropagation()
      if (this.parentElement.classList.contains('playing')) {
        this.parentElement.className = this.parentElement.className.replace(" playing", '');
        // video.controls = false;
      }
    };
    video.onplay = function(e) {
      e.stopPropagation()
      if (!this.parentElement.classList.contains('playing')) {
        video.parentElement.className += " playing"
      }
    };
  }

  async function playVideo(video) {
    try {
      await video.play();
      video.controls = true;
      video.parentElement.className += " playing"
    } catch (err) {
      video.parentElement.className = video.parentElement.className.replace(" playing", '');
    }
  }

  function handlePlayButton(video) {
    if (video.paused) {
      playVideo(video);
    } else {
      video.pause();
      video.controls = false;
      video.parentElement.className = video.parentElement.className.replace(" playing", '');
    }
  }

  if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector ||
      Element.prototype.webkitMatchesSelector;
  }

  if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
      var el = this;

      do {
        if (el.matches(s)) return el;
        el = el.parentElement || el.parentNode;
      } while (el !== null && el.nodeType === 1);
      return null;
    };
  }

  var classname = document.getElementsByClassName("schema-faq-question");
  Array.from(classname).forEach(function(element) {
    element.addEventListener('click', function() {
      console.log(this.closest('.schema-faq-section'));
      if (this.closest('.schema-faq-section').classList.contains('open')) {
        this.closest('.schema-faq-section').classList.remove("open");
      } else {
        this.closest('.schema-faq-section').classList.add('open');
      }
    });
  });

  var country_selector = document.getElementById('country_selector');
  var country_model = document.getElementById('country_model');
  // elem.addEventListener('click', function(e) {

  // });
</script>

<?php if(get_the_ID() == 1006) : ?>

  <!-- SG Facebook Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window,document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
   fbq('init', '1790607391181863'); 
  fbq('track', 'PageView');
  </script>
  <noscript>
   <img height="1" width="1" 
  src="https://www.facebook.com/tr?id=1790607391181863&ev=PageView
  &noscript=1"/>
  </noscript>
  <!-- End Facebook Pixel Code -->

<?php else: ?>

  <!-- INTL Facebook Pixel Code -->
  <script>
   !function(f,b,e,v,n,t,s)
   {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
   n.callMethod.apply(n,arguments):n.queue.push(arguments)};
   if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
   n.queue=[];t=b.createElement(e);t.async=!0;
   t.src=v;s=b.getElementsByTagName(e)[0];
   s.parentNode.insertBefore(t,s)}(window, document,'script',
   'https://connect.facebook.net/en_US/fbevents.js');
   fbq('init', '1524525910935559');
   fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
   src="https://www.facebook.com/tr?id=1524525910935559&ev=PageView&noscript=1"
  /></noscript>
  <!-- End Facebook Pixel Code -->

<?php endif; ?>


<?php if (isset($_GET['testeo'])) : ?>
  <?php  /* menu */
  wp_nav_menu(array(
    'menu'              => 'country-menu',
    'theme_location'    => 'country-menu',
    'walker'         => new Walker_Nav_Menu_Dropdown(),
    'items_wrap'     => '<select class="js-example-basic-single">%3$s</select>',
  ));
  ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="st`ylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
  <script>
    $ = jQuery;
    $(document).ready(function() {
      $('.js-example-basic-single').select2();
    });
  </script>
<?php endif; ?>

</body>

</html>