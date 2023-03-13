// AJAX call to get messages
var xhr = new XMLHttpRequest();
xhr.open('GET', 'data.json', true);
xhr.onreadystatechange = function() {
  if (xhr.readyState === 4 && xhr.status === 200) {
    var data = JSON.parse(xhr.responseText);
    var messages = document.getElementById("messages");
    // loop through messages and add them to the page
    for (var i = 0; i < data.length; i++) {
      var entry = document.createElement("div");
      entry.classList.add("comment");
      var paragraph = document.createElement("p");
      var name = document.createElement("strong");
      name.textContent = data[i].name;
      paragraph.appendChild(name);
      paragraph.appendChild(document.createTextNode(": " + data[i].message));
      entry.appendChild(paragraph);

      // add reply div if reply is not empty
      if(data[i].rname != "" || data[i].rmessage != "") {
        var reply = document.createElement("div");
        reply.classList.add("reply");
        var reply_name = document.createElement("strong");
        reply_name.textContent = data[i].rname;
        var reply_message = document.createElement("p");
        reply_message.appendChild(reply_name);
        reply_message.appendChild(document.createTextNode(": " + data[i].rmessage));
        reply.appendChild(reply_message);
        entry.appendChild(reply);
      }
      messages.appendChild(entry);
    }
  }
};
xhr.send();
