# Share permissions
Provide a way to provide allow/block lists for sharing based on group membership

## Usage
This app currently only works via OCC and has no graphical User Interface.

### Add a group to the sharepermission list
`occ sharepermissions:add <groupId>`

One example for a group with the groupID `admin` is:

`occ sharepermissions:add admin`

### Change the permission mode (block or allow)
`occ sharepermissions:mode block`

To block all groups on this list from sharing. (Blacklist)

`occ sharepermissions:mode allow`

To limit sharing to all groups on this list. (Whitelist)

### Remove a group from the permission list
`occ sharepermissions:remove <groupId>`

One example for a group with the groupID `admin` is:

`occ sharepermissions:remove admin`

### Show the current mode and groups listed for share permissions
`occ sharepermissions:show`

This will return a list with all from sharing blocked/allowed groups.
