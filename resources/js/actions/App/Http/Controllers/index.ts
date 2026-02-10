import Admin from './Admin'
import Settings from './Settings'
import Kps from './Kps'
const Controllers = {
    Admin: Object.assign(Admin, Admin),
Settings: Object.assign(Settings, Settings),
Kps: Object.assign(Kps, Kps),
}

export default Controllers