  window.onload = function init() {
    if(id_recorder_data.value != ''){
        var json = JSON.parse(id_recorder_data.value);
        var au = document.createElement('audio');
        au.controls = true;
        au.src = json[0];
        recordingslist.appendChild(au);
    }
  };

  qtypeInterviewQuestionControll.addEventListener('click', function(e) {
      e.preventDefault();
      if(typeof(qtypeInterviewQuestionPlayer)!='undefined'){qtypeInterviewQuestionPlayer.remove()};
      var au = document.createElement('audio');
      au.id = 'qtypeInterviewQuestionPlayer';
      au.src = e.target.getAttribute('data');
      au.className = 'hidden';
      document.getElementsByTagName('body')[0].appendChild(au);
      qtypeInterviewQuestionPlayer.play();
  });
