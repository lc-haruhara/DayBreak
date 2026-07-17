<div class=""
  data-js-table-of-contents-target
  data-js-auto-hyper-link>

  <?php if (have_rows('FlexibleContents')): //柔軟コンテンツフィールドチェック 
  ?>
    <?php while (have_rows('FlexibleContents')) : the_row(); //ループ 
    ?>

      <?php if (get_row_layout() == 'Col1Layout'): //Col1Layoutがあった場合に出力 
      ?>
        <div class="c-swe-grid">
          <!-- Col1 -->
          <div class="grid">
            <?php include('SandwichEditorDefaultModules/Col1.php'); ?>
          </div>
        </div>

      <?php elseif (get_row_layout() == 'Col2Layout'): //Col2Layoutがあった場合に出力 
      ?>
        <div class="c-swe-grid _2col">
          <!-- Col1 -->
          <div class="grid">
            <?php include('SandwichEditorDefaultModules/Col1.php'); ?>
          </div>
          <!-- Col2 -->
          <div class="grid">
            <?php include('SandwichEditorDefaultModules/Col2.php'); ?>
          </div>
        </div>

      <?php elseif (get_row_layout() == 'Col3Layout'): //Col3Layoutがあった場合に出力 
      ?>
        <div class="c-swe-grid _3col">
          <!-- Col1 -->
          <div class="grid">
            <?php include('SandwichEditorDefaultModules/Col1.php'); ?>
          </div>
          <!-- Col2 -->
          <div class="grid">
            <?php include('SandwichEditorDefaultModules/Col2.php'); ?>
          </div>
          <!-- Col3 -->
          <div class="grid">
            <?php include('SandwichEditorDefaultModules/Col3.php'); ?>
          </div>
        </div>

      <?php elseif (get_row_layout() == 'HeadingBigLayout'): //HeadingBigLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/HeadingBig.php'); ?>

      <?php elseif (get_row_layout() == 'HeadingSmallLayout'): //HeadingSmallLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/HeadingSmall.php'); ?>

      <?php elseif (get_row_layout() == 'ListLayout'): //ListLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/List.php'); ?>

      <?php elseif (get_row_layout() == 'ListNameLayout'): //ListNameLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/ListName.php'); ?>

      <?php elseif (get_row_layout() == 'TableLayout'): //TableLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/Table.php'); ?>

      <?php elseif (get_row_layout() == 'YoutubeLayout'): //YoutubeLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/Youtube.php'); ?>

      <?php elseif (get_row_layout() == 'FileLayout'): //FileLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/File.php'); ?>

      <?php elseif (get_row_layout() == 'LinkButtonLayout'): //LinkButtonLayoutがあった場合に出力 
      ?>
        <?php include('SandwichEditorDefaultModules/LinkButton.php'); ?>

        <!--- EndOfFields ----------------------------------------->
      <?php endif; ?>
    <?php endwhile; ?>
  <?php endif; ?>

</div>