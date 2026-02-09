import DashboardController from './DashboardController'
import JobAnalyticsController from './JobAnalyticsController'
import JobController from './JobController'
import KewApprovalController from './KewApprovalController'
import JobAssignmentController from './JobAssignmentController'
import JobNoteController from './JobNoteController'
import CustomerController from './CustomerController'
import InspectionController from './InspectionController'
import PhotoController from './PhotoController'
import RepairCompletionController from './RepairCompletionController'
import Admin from './Admin'
import Settings from './Settings'
import Kps from './Kps'
const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
JobAnalyticsController: Object.assign(JobAnalyticsController, JobAnalyticsController),
JobController: Object.assign(JobController, JobController),
KewApprovalController: Object.assign(KewApprovalController, KewApprovalController),
JobAssignmentController: Object.assign(JobAssignmentController, JobAssignmentController),
JobNoteController: Object.assign(JobNoteController, JobNoteController),
CustomerController: Object.assign(CustomerController, CustomerController),
InspectionController: Object.assign(InspectionController, InspectionController),
PhotoController: Object.assign(PhotoController, PhotoController),
RepairCompletionController: Object.assign(RepairCompletionController, RepairCompletionController),
Admin: Object.assign(Admin, Admin),
Settings: Object.assign(Settings, Settings),
Kps: Object.assign(Kps, Kps),
}

export default Controllers