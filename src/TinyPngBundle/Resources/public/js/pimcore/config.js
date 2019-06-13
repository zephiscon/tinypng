/** global: pimcore */
pimcore.registerNS("zephis.tinypng.config");
zephis.tinypng.config = Class.create({

    initialize: function () {

        this.getData();
    },

    getData: function () {
        Ext.Ajax.request({
            url: "/admin/tinypng/get-config",
            success: function (response) {

                this.data = Ext.decode(response.responseText);
                this.getTabPanel();

            }.bind(this)
        });
    },

    getTabPanel: function () {

        if (!this.panel) {
            this.panel = Ext.create('Ext.panel.Panel', {
                id: "zephis_tinypng_config",
                title: t("tinypng_config"),
                iconCls: "pimcore_icon_system",
                border: false,
                layout: "fit",
                closable: true
            });

            this.panel.on("destroy", function () {
                pimcore.globalmanager.remove("zephis_tinypng_config");
            }.bind(this));

            this.layout = Ext.create('Ext.form.Panel', {
                bodyStyle: 'padding:20px 5px 20px 5px;',
                border: false,
                autoScroll: true,
                forceLayout: true,
                defaults: {
                    forceLayout: true
                },
                fieldDefaults: {
                    labelWidth: 250
                },
                buttons: [
                    {
                        text: t("save"),
                        handler: this.save.bind(this),
                        iconCls: "pimcore_icon_apply"
                    }
                ],
                items: [
                    {
                        xtype: 'fieldset',
                        title: t('tinypng_config'),
                        defaultType: 'textfield',
                        defaults: {width: 450},
                        items: [
                            {
                                fieldLabel: t("tinypng_api_key"),
                                xtype: "textfield",
                                name: "config.apiKey",
                                value: this.data.config.apiKey,
                                width: 450
                            }
                        ]
                    }
                ]
            });

            this.panel.add(this.layout);

            var tabPanel = Ext.getCmp("pimcore_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.setActiveItem(this.panel);

            pimcore.layout.refresh();
        }

        return this.panel;
    },

    save: function () {
        var values = this.layout.getForm().getFieldValues();

        Ext.Ajax.request({
            url: "/admin/tinypng/set-config",
            method: "PUT",
            params: {
                data: Ext.encode(values)
            },
            success: function (response) {
                try {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        pimcore.helpers.showNotification(t("success"), t("saved_successfully"), "success");
                    } else {
                        pimcore.helpers.showNotification(t("error"), t("saving_failed"),
                            "error", t(res.message));
                    }
                } catch (e) {
                    pimcore.helpers.showNotification(t("error"), t("saving_failed"), "error");
                }
            }
        });
    },
});
