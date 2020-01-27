$(function() {
  var $good = $('.my-good'), goodPostId;
  $good.on('click', function(e) {
    e.stopPropagation();
    var $this = $(this);
    goodPostId = $this.parents('.post').data('postid');
    $.ajax({
      type: 'POST',
      url: '../ajaxGood.php',
      data: { postId: goodPostId }
    }).done(function(data) {
      console.log('Ajax Success');

      $this.children('span').html(data);
      $this.children('i').toggleClass('far');
      $this.children('i').toggleClass('fas');
      $this.children('i').toggleClass('active');
      $this.toggleClass('active');
    }).fail(function(msg) {
      console.log('Ajax Error');
    });

  });
});

$(function() {
  var $profile = $('#done'), profileId;

  $profile.on('click', function(e) {
    e.stopPropagation();
    var $this = $(this);
    profileId = $this.parents('.profile_ajax').data('profileid');
    $.ajax({
      type: 'POST',
      url: '../ajaxProfile.php',
      data: {
        prId: profileId,
        nameId: mainForm.names.value,
        my_prId: mainForm.my_prs.value
       }
    }).done(function(data) {
        console.log('Ajax Success');
        $('.names').html(mainForm.names.value);
        $('.my_pr').html(mainForm.my_prs.value);
      }).fail(function(msg) {
      console.log('Ajax Error');
    });
  });
});

$(function() {
  var $profile = $('#img-dlt-btn'), profileId;

  $profile.on('click', function(e) {
    e.stopPropagation();
    var $this = $(this);
    profileId = $this.parents('.profile_ajax').data('profileid');
    $.ajax({
      type: 'POST',
      url: '../ajaxProfile.php',
      data: {
        prId2: profileId,
        imgId: mainForm.img_delete.value
       }
    }).done(function(data) {
        console.log('Ajax Success');
        $('.icons').attr('src', mainForm.img_delete.value + 1);
      }).fail(function(msg) {
      console.log('Ajax Error');
    });
  });
});

$(function() {
  // アップロードするファイルを選択
  $('input[type=file]').change(function() {
    var file = $(this).prop('files')[0];
    // アップロード
    var fd = new FormData();
    fd.append($(this).attr('name'), file);
    $.ajax({
      url: '../ajaxProfile.php',
      type: 'POST',
      data: fd,
      processData: false, // jQueryがデータを処理しないよう指定
      contentType: false  // jQueryがcontentTypeを設定しないよう指定
    }).done(function(result) {
      console.log('Ajax Success');
      // 実行中
    }).fail(function() {
      // 失敗
      $('#result').html('失敗');
    }).always(function(result) {
      // 完了
      if (result.state == 'success') {
        $('#result').html('成功');
      } else {
        $('#result').html('失敗');
      }
    });
  });
});



$(function($){
    $('.toggle_btn').click(function(){
        //toggleでボタンの表示と非表示を切り替える
        $($(this).data('area')).toggle();
        //ボタンのテキストを変更
        if($($(this).data('area')).css('display') == 'none'){
            $(this).val('表示する');
        }else{
            $(this).val('非表示にする');
        }
    });
});
