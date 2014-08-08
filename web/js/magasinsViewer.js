$(function () {
  $.each([1,2,3,4], function($i, $n) {
    document.getElementById('link' + $n).onclick = function (event) {
      event = event || window.event;
      var target = event.target || event.srcElement,
      link = target.src ? target.parentNode : target,
      options = {container: '#container' + $n, index: link, event: event},
      links = this.getElementsByTagName('a');
      blueimp.Gallery(links, options);
    };
  });
});