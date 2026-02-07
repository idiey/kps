import WorkshopController from './WorkshopController'
import WorkshopUserController from './WorkshopUserController'
import WorkshopAnalyticsController from './WorkshopAnalyticsController'
import RoleManagementController from './RoleManagementController'
import UserManagementController from './UserManagementController'
import ReportController from './ReportController'
import AssetController from './AssetController'
import InventoryController from './InventoryController'
import SettingsController from './SettingsController'
const Admin = {
    WorkshopController: Object.assign(WorkshopController, WorkshopController),
WorkshopUserController: Object.assign(WorkshopUserController, WorkshopUserController),
WorkshopAnalyticsController: Object.assign(WorkshopAnalyticsController, WorkshopAnalyticsController),
RoleManagementController: Object.assign(RoleManagementController, RoleManagementController),
UserManagementController: Object.assign(UserManagementController, UserManagementController),
ReportController: Object.assign(ReportController, ReportController),
AssetController: Object.assign(AssetController, AssetController),
InventoryController: Object.assign(InventoryController, InventoryController),
SettingsController: Object.assign(SettingsController, SettingsController),
}

export default Admin