var sms = {

	// Route par défaut vers l'API
  route: "../app/sms.php?mode=queue",

  // Appel du WebService d'ajout de SMS.
  send: function(email, tel, text, onSuccessCallback, onErrorCallback) {
		if ( sms.check(email, tel, text) ) {
			var url = sms.route+"&email="+email+"&tel="+tel+"&msg="+encodeURI(text);
			$.get(url, function(r) {
				if (r) {
          if (onSuccessCallback != null) onSuccessCallback.call();
				} else {
					if (onErrorCallback != null) onErrorCallback.call();
				}
			},'json');
    }
	},

	// Vérifie la validité des données envoyées
	check: function(email, tel, text) {
		var allOK = true;
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  	allOK = re.test(email);

  	if (allOK) {
  		allOK = (tel != "" && text != ""); //TODO: Remplacer ici par un test un peu plus solide...
  	}

  	return allOK;
  },

};