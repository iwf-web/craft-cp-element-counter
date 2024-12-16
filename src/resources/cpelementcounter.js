document.addEventListener('DOMContentLoaded', async () => {

    var href = location.href;
    var urlInCategories = href.match(/\/categories/);
    var urlInUsers = href.match(/\/users/);
    var urlInAssets = href.match(/\/assets/);

    let hasSections = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/section:\d+/);
    }).length > 0;

    let hasGroups = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/group:\d+/);
    }).length > 0;

    let hasFolders = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/volume:/);
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
    if (urlInAssets.length && hasFolders) {
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

        $('#main-content.has-sidebar .sidebar li a[data-key]').each(function () {
            if ($(this).data('key').match(/section:/)) {
                uids.push($(this).data('key').replace('section:', ''));
            }
        });

        return uids;
    }

    function getGroupUids() {
        var uids = [];

        $('#main-content.has-sidebar .sidebar li a[data-key]').each(function () {
            if ($(this).data('key').match(/group:/)) {
                uids.push($(this).data('key').replace('group:', ''));
            }
        });

        return uids;
    }


    function getFolders() {
        var folders = [];

        $('#main-content.has-sidebar .sidebar li a[data-folder-id]').each(function () {
            var folderKeys = $(this).data('folder-id');
            folders.push(folderKeys);

        });

        return folders;
    }

});
