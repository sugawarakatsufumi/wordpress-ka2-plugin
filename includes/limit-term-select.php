<script type="text/javascript">
  jQuery(function($) {
    //記事編集画面
    let categorydiv = $( '#categorydiv input[type=checkbox]' );
    categorydiv.click( function() {
      $(this).parents( '#categorydiv' ).find( 'input[type=checkbox]' ).prop('checked', false);
      $(this).prop( 'checked', true );
    });
    //一覧のクイック編集
    let inline_edit_col_center = $( '.inline-edit-col-center input[type=checkbox]' );
    inline_edit_col_center.click( function() {
      $(this).parents( '.inline-edit-col-center' ).find( 'input[type=checkbox]' ).prop( 'checked', false );
      $(this).prop( 'checked', true );
    });
    $( '#categorydiv #category-pop > ul > li:first-child, #categorydiv #category-all > ul > li:first-child, .inline-edit-col-center > ul.category-checklist > li:first-child' ).before( '<p style="padding-top:5px;">カテゴリーは1つしか選択できません</p>' );
    //エリア選択の制限
    let termdiv = $( '#areachecklist input[type=checkbox]' );
    termdiv.click( function() {
      $(this).parents( '#areachecklist' ).find( 'input[type=checkbox]' ).prop('checked', false);
      let val = $(this).val();
      if(val== 77 || val== 47 || val== 62 || val== 71){
        alert('地方は選択できません');
        $(this).prop( 'checked', false );
      }else{
        $(this).prop( 'checked', true );
      }
      
      //alert($(this).parents('#categorydiv').attr('id'));
    });
  });
</script>