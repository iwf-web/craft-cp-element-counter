document.addEventListener('DOMContentLoaded', async () => {

    var href = location.href;
    var urlInCategories = href.match(/\/categories/);
    var urlInUsers = href.match(/\/users/);
    var urlInAssets = href.match(/\/assets/);

    let hasSections = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/section:\w+/);
    }).length > 0;

    let hasGroups = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/group:\w+/);
    }).length > 0;

    let hasFolders = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/volume:\w+/);
    }).length > 0;

    if (hasSections) {
        getEntriesCount();
    }
    if (urlInCategories && hasGroups) {
        getCategoriesCount();
    }
    if (urlInUsers && hasGroups) {
        getUsersCount();
    }
    if (urlInAssets && hasFolders) {
        getAssetsCount();
    }

    function addCountToAnchor(val, anchor) {
        let nodesArray = Array.from(anchor); // Convert NodeList to Array
        nodesArray.forEach(pill => {
            pill.innerHTML += '<span class="cpelementcount-pill">' + val + '</span>';
        });
    }


    function getEntriesCount() {
        var uids = getSectionUids();

        Craft.postActionRequest('control-panel-element-counter/count/get-entries-count', {uids: uids},
            function (result) {
                uids.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="section:' + val + '"]');
                        if (anchor.length > 0) {
                            addCountToAnchor(result[val], anchor);
                        }
                    }
                });

                if (typeof result['*'] !== 'undefined') {
                    var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="*"]');
                    if (anchor.length > 0) {
                        addCountToAnchor(result['*'], anchor);
                    }
                }
            }
        );
    }

    function getCategoriesCount() {
        var uids = getGroupUids();

        Craft.postActionRequest('control-panel-element-counter/count/get-categories-count', {uids: uids},
            function (result) {
                uids.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="group:' + val + '"]');
                        if (anchor.length > 0) {
                            addCountToAnchor(result[val], anchor);
                        }
                    }
                });
            }
        );
    }

    function getUsersCount() {
        var uids = getGroupUids();

        Craft.postActionRequest('control-panel-element-counter/count/get-users-count', {uids: uids},
            function (result) {
                uids.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="group:' + val + '"]');
                        if (anchor.length > 0) {
                            addCountToAnchor(result[val], anchor);
                        }
                    }
                });

                var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="*"]');
                if (anchor.length > 0) {
                    addCountToAnchor(result['*'], anchor);
                }

                var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="admins"]');
                if (anchor.length > 0) {
                    addCountToAnchor(result['admins'], anchor);
                }

            }
        );
    }

    function getAssetsCount() {
        var folders = getFolders();

        Craft.postActionRequest('control-panel-element-counter/count/get-assets-count', {folders: folders},
            function (result) {
                folders.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-folder-id="' + val + '"]');
                        if (anchor.length > 0) {
                            addCountToAnchor(result[val], anchor);
                        }
                    }
                });
            }
        );
    }



    function getSectionUids() {
        var uids = [];

        let elements = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]');
        elements.forEach(function(element) {
            let key = element.getAttribute('data-key');
            if (key.match(/section:/)) {
                uids.push(key.replace('section:', ''));
            }
        });

        return uids;
    }

    function getGroupUids() {
        var uids = [];

        let elements = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]');
        elements.forEach(function(element) {
            let key = element.getAttribute('data-key');
            if (key.match(/group:/)) {
                uids.push(key.replace('group:', ''));
            }
        });

        return uids;
    }


    function getFolders() {
        var folders = [];

        let elements = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-folder-id]');
        elements.forEach(function(element) {
            let folderKeys = element.getAttribute('data-folder-id');
            folders.push(folderKeys);
        });

        return folders;
    }

});
