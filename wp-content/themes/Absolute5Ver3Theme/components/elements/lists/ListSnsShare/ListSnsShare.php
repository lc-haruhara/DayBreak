	<?php
  $page_url = get_permalink();
  $page_title = trim(single_post_title('', false));
  $site_title = trim(wp_title('', false));
  $page_url_encoded = urlencode(mb_convert_encoding($page_url, "UTF-8"));
  $page_title_encoded = urlencode(mb_convert_encoding($page_title, "UTF-8"));
  $site_title_encoded = urlencode(mb_convert_encoding($site_title, "UTF-8"));
  ?>
	<ul class="c-list-sns-share">
	  <!-- Facebook -->
	  <li class="item _facebook">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $page_url_encoded ?>&text=<?php echo $site_title_encoded ?>">
	      <?php require INCLUDE_SVG . 'icons/sns/color/facebook.svg'; ?>
	    </a>
	  </li>
	  <!-- Twitter -->
	  <li class="item _twitter">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://twitter.com/intent/tweet?source=&text=<?php echo $site_title_encoded ?>%0a<?php echo $page_url_encoded ?>">
	      <?php require INCLUDE_SVG . 'icons/sns/color/x.svg'; ?>
	    </a>
	  </li>
	  <!-- Threads -->
	  <li class="item _threads">
	    <a class="item-body" target="_blank" rel="noopener noreferrer" href="https://www.threads.net/intent/post?text=<?php echo $site_title_encoded ?>%0a<?php echo $page_url_encoded ?>">
	      <?php require INCLUDE_SVG . 'icons/sns/color/threads.svg'; ?>
	    </a>
	  </li>
	  <!-- Bluesky -->
	  <li class="item _bluesky">
	    <a class="item-body" target="_blank" rel="noopener noreferrer" href="https://bsky.app/intent/compose?text=<?php echo $site_title_encoded ?>%0a<?php echo $page_url_encoded ?>">
	      <?php require INCLUDE_SVG . 'icons/sns/color/bluesky.svg'; ?>
	    </a>
	  </li>
	  <!-- LINE -->
	  <li class="item _line">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://line.naver.jp/R/msg/text/<?php echo $site_title_encoded ?> <?php echo $page_url_encoded ?>">
	      <?php require INCLUDE_SVG . 'icons/sns/color/line.svg'; ?>
	    </a>
	  </li>
	  <!-- はてなブックマーク -->
	  <li class="item _hateb">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php echo $page_url_encoded ?>&title=<?php echo $page_title_encoded ?>｜<?php echo $site_title_encoded ?>" target="_blank" rel="noopener noreferrer">
	      <?php require INCLUDE_SVG . 'icons/sns/color/hatebu.svg'; ?>
	    </a>
	  </li>
	  <!-- Pocket -->
	  <li class="item _pocket">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://getpocket.com/edit?url=<?php echo $page_url_encoded ?>&title=<?php echo $page_title_encoded ?>" onclick="window.open(this.href, 'PCwindow', 'width=550, height=350, menubar=no, toolbar=no, scrollbars=yes'); return false;">
	      <?php require INCLUDE_SVG . 'icons/sns/color/pocket.svg'; ?>
	    </a>
	  </li>
	  <!-- Feedly -->
	  <li class="item _feedly">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://feedly.com/i/subscription/feed/<?php echo get_home_url(); ?>/feed/">
	      <?php require INCLUDE_SVG . 'icons/sns/color/feedly.svg'; ?>
	    </a>
	  </li>
	  <!-- note -->
	  <li class="item _note">
	    <a class="" target="_blank" rel="noopener noreferrer" href="https://note.com/intent/post?url=<?php echo $page_url_encoded ?>&ref=<?php echo $page_url_encoded ?>">
	      <?php require INCLUDE_SVG . 'icons/sns/color/note.svg'; ?>
	    </a>
	  </li>
	  <!-- Pinterest -->
	  <li class="item _pinterest">
	    <a class="" target="_blank" rel="noopener noreferrer" href="http://pinterest.com/pin/create/button/" data-pin-custom="true" data-pin-do="buttonBookmark" data-pin-log="button_pinit_bookmarklet" data-pin-href="https://www.pinterest.com/pin/create/button/">
	      <?php require INCLUDE_SVG . 'icons/sns/color/pinterest.svg'; ?>
	    </a>
	    <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
	  </li>
	  <!-- Rss -->
	  <li class="item _rss">
	    <a class="" target="_blank" rel="noopener noreferrer" href="<?php $url = home_url(); ?>/feed">
	      <?php require INCLUDE_SVG . 'icons/sns/color/rss.svg'; ?>
	    </a>
	  </li>
	</ul>