pimcore.registerNS("pimcore.plugin.ExtendmateLoginTrackerBundle");

pimcore.plugin.ExtendmateLoginTrackerBundle = Class.create({
  initialize: function () {
    document.addEventListener(
      pimcore.events.pimcoreReady,
      this.pimcoreReady.bind(this)
    );
  },

  pimcoreReady: function (e) {
    console.log("ExtendmateLoginTrackerBundle ready!");
    this.fetchBundleSettings();
  },

  fetchBundleSettings: function () {
    Ext.Ajax.request({
      url: Routing.generate("extendmate_login_tracker_bundle_admin_settings"),
      success: function (response) {
        let result = Ext.decode(response.responseText);
        let lastSeen = result.last_seen;
        if (lastSeen.enabled) {
          this.interval = setInterval(
            this.sendRequest.bind(this),
            lastSeen.interval * 1000
          );
        }
      }.bind(this),
      failure: function (response) {
        console.error("error while fetching the bundle settings:", response);
      },
    });
  },

  sendRequest: function () {
    Ext.Ajax.request({
      url: Routing.generate("extendmate_login_tracker_bundle_admin_default"),
      failure: function (response) {
        console.error("error while updating the last seen time:", response);
      },
    });
  },
});

var ExtendmateLoginTrackerBundle =
  new pimcore.plugin.ExtendmateLoginTrackerBundle();