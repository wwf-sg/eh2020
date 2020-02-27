</div><!-- #content -->

<footer id="colophon" class="site-footer" style="background-color: #fff !important">
  <div class="container site-info text-center py-5">
    <p>&copy; 2020 WWF - World Wide Fund For Nature. All Rights Reserved.</p>
  </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

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
</script>

</body>

</html>