  window.onload = function init() {
      var interviewItems = document.querySelectorAll('.qtypeInterview .answer .recorder_data');
      for(var i=0; i<interviewItems.length; i++){
          var recorder_data = interviewItems[i];
          var parent = findParentBySelector(recorder_data, '.qtypeInterview');
          var audioWrapper = parent.querySelector('.audioWrapper');
          if(recorder_data.value != ''){
              var json = JSON.parse(recorder_data.value);
              var au = document.createElement('audio');
              au.controls = true;
              au.src = json[0];
              audioWrapper.appendChild(au);
          }
      }
      bindElementBySelector('.qtypeInterview .playControll','click',function(e){
          e.preventDefault();
          var parent = findParentBySelector(e.target, '.qtypeInterview');
          var questionPlayer = parent.querySelector('.questionPlayer');
          if(typeof(questionPlayer)!='undefined' && questionPlayer != null){questionPlayer.remove()};
          var au = document.createElement('audio');
          au.className = 'questionPlayer hidden';
          au.src = e.target.getAttribute('data');
          parent.appendChild(au);
          var questionPlayer = parent.querySelector('.questionPlayer');
          questionPlayer.play();
      });

};
