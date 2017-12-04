/**
 *
 * @author iMarkus
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

(function () {
    angular.module('piwikApp').controller('ArchiveSiteController', ArchiveSiteController);

    ArchiveSiteController.$inject = ['$scope', 'piwikApi'];

    function ArchiveSiteController($scope, piwikApi) {

        $scope.loading = false;
        $scope.site = {
            id: 'all',
            name: ''
        };
		$scope.dateRange = '';
		$scope.segment = '';        
		$scope.disableScheduledTasks = 1;
		$scope.disableSegmentsArchiving = 0;
		$scope.availableSegments = {
            '': _pk_translate('ArchiveSite_AllSegments')
        };
		
		$scope.availableSegmentIds = {
            '': ''
        };

        $scope.archive = function () {
            $('#confirmArchiving .website').html($scope.site.id == 'all' ? $scope.site.name : (_pk_translate('General_Website') + ' ' + $scope.site.name));			
            $('#confirmArchiving .segment').text($scope.segment ? (_pk_translate('General_Segment') + ' ' + $scope.availableSegments[$scope.segment]) : _pk_translate('ArchiveSite_AllSegments'));
            piwikHelper.modalConfirm('#confirmArchiving', {yes: archiveSite});
        };

        $scope.fetchSegments = function() {
            piwikApi.withTokenInUrl();
            piwikApi.fetch({
                method: 'SegmentEditor.getAll',
                idSite: $scope.site.id
            }).then(function (segments) {				
				var availSegments = {
                    '': _pk_translate('ArchiveSite_AllSegments')
                };
				var availSegmentIds = {
                    '': ''
                };
                angular.forEach(segments, function(segment) {
                    availSegments[segment.definition] = segment.name + ' (' + segment.definition + ')';
					availSegmentIds[segment.definition] = segment.idsegment;
                });

                $scope.availableSegments = availSegments;
                $scope.availableSegmentIds = availSegmentIds;
				
                if (!($scope.segment in $scope.availableSegments)) {					
                    $scope.segment = '';
                }				
                if (!($scope.segment in $scope.availableSegmentIds)) {					
                    $scope.segment = '';
                }
            });
        };

        function archiveSite() {
            $scope.loading = true;
            piwikApi.withTokenInUrl();
            piwikApi.fetch({
                module: 'ArchiveSite',
                action: 'archiveSite',
                idSites: $scope.site.id,
                dateRange: $scope.dateRange,
                idSegments: $scope.availableSegmentIds[$scope.segment],
                disableScheduledTasks: $scope.disableScheduledTasks,
                disableSegmentsArchiving: $scope.disableSegmentsArchiving                
            }).then(function (response) {
                var UI = require('piwik/UI');
                var notification = new UI.Notification();                
                notification.show(response.value, {id: 'ArchiveSite', context: 'success'});
                notification.scrollToNotification();
                $scope.loading = false;
            }, function (errorMessage) {
                $scope.loading = false;
            });
        }
		
        $scope.fetchSegments();
    }
})();