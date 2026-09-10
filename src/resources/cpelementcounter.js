document.addEventListener('DOMContentLoaded', async () => {

    var href = location.href;
    var urlInCategories = href.match(/\/categories/);
    var urlInUsers = href.match(/\/users/);
    var urlInAssets = href.match(/\/assets/);
    var urlInEvents = href.match(/\/events(\/|$|\?)/);
    var urlInCommerceOrders = href.match(/\/commerce\/orders/);
    var urlInFormieSubmissions = href.match(/\/formie\/submissions/);
    var urlInFormieSentNotifications = href.match(/\/formie\/sent-notifications/);

    let hasSections = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/section:\w+/);
    }).length > 0;

    let hasGroups = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/group:\w+/);
    }).length > 0;

    let hasFolders = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/volume:\w+/);
    }).length > 0;

    let hasEventTypes = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/eventType:\w+/);
    }).length > 0;

    let hasCarts = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/^carts:/);
    }).length > 0;

    let hasFormieForms = Array.from(document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]')).filter(function (element) {
        return element.getAttribute('data-key').match(/^form:\d+$/);
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
    if (urlInEvents && hasEventTypes) {
        getEventsCount();
    }
    if (urlInCommerceOrders && hasCarts) {
        getCartsCount();
    }
    if (urlInFormieSubmissions && hasFormieForms) {
        getSubmissionsCount();
    }
    if (urlInFormieSentNotifications && hasFormieForms) {
        getSentNotificationsCount();
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

    function getEventsCount() {
        var uids = getEventTypeUids();

        Craft.postActionRequest('control-panel-element-counter/count/get-events-count', {uids: uids},
            function (result) {
                uids.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="eventType:' + val + '"]');
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

    function getCartsCount() {
        var keys = getCartKeys();

        Craft.postActionRequest('control-panel-element-counter/count/get-carts-count', {keys: keys},
            function (result) {
                keys.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="' + val + '"]');
                        if (anchor.length > 0) {
                            addCountToAnchor(result[val], anchor);
                        }
                    }
                });
            }
        );
    }

    function getSubmissionsCount() {
        var formIds = getFormieFormIds();

        Craft.postActionRequest('control-panel-element-counter/count/get-submissions-count', {formIds: formIds},
            function (result) {
                formIds.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="form:' + val + '"]');
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

    function getSentNotificationsCount() {
        var formIds = getFormieFormIds();

        Craft.postActionRequest('control-panel-element-counter/count/get-sent-notifications-count', {formIds: formIds},
            function (result) {
                formIds.forEach(function(val, i) {
                    if (typeof result[val] !== 'undefined') {
                        var anchor = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key="form:' + val + '"]');
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


    function getEventTypeUids() {
        var uids = [];

        let elements = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]');
        elements.forEach(function(element) {
            let key = element.getAttribute('data-key');
            if (key.match(/eventType:/)) {
                uids.push(key.replace('eventType:', ''));
            }
        });

        return uids;
    }

    function getCartKeys() {
        var keys = [];

        let elements = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]');
        elements.forEach(function(element) {
            let key = element.getAttribute('data-key');
            if (key.match(/^carts:/)) {
                keys.push(key);
            }
        });

        return keys;
    }

    function getFormieFormIds() {
        var ids = [];

        let elements = document.querySelectorAll('#main-content.has-sidebar .sidebar li a[data-key]');
        elements.forEach(function(element) {
            let key = element.getAttribute('data-key');
            let match = key.match(/^form:(\d+)$/);
            if (match) {
                ids.push(match[1]);
            }
        });

        return ids;
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
