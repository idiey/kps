import workshops from './workshops'
import roles from './roles'
import users from './users'
import reports from './reports'
import assets from './assets'
import inventory from './inventory'
import settings from './settings'
const admin = {
    workshops: Object.assign(workshops, workshops),
roles: Object.assign(roles, roles),
users: Object.assign(users, users),
reports: Object.assign(reports, reports),
assets: Object.assign(assets, assets),
inventory: Object.assign(inventory, inventory),
settings: Object.assign(settings, settings),
}

export default admin