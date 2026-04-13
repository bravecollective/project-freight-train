jQuery(document).ready(function () {

    var overridePopoverTriggerList = [].slice.call($(".issues-popover"))
    var overridePopoverList = overridePopoverTriggerList.map(function (eachPopover) {
      return new bootstrap.Popover(eachPopover, {trigger: "focus"})
    })
    
});