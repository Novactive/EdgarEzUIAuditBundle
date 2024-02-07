const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ibexa-admin-ui-layout-css',
        newItems: [path.resolve(__dirname, '../public/css/edgarezuiaudit.scss')]
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ibexa-admin-ui-layout-js',
        newItems: [
            path.resolve(__dirname, '../public/js/edgarezuiaudit.js'),
            path.resolve(__dirname, '../public/js/edgardashboard.js'),
        ]
    });
};
