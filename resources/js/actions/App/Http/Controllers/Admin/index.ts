import RoleManagementController from './RoleManagementController'
import UserManagementController from './UserManagementController'
const Admin = {
    RoleManagementController: Object.assign(RoleManagementController, RoleManagementController),
UserManagementController: Object.assign(UserManagementController, UserManagementController),
}

export default Admin