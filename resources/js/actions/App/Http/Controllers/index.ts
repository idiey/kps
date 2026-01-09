import DashboardController from './DashboardController'
import JobController from './JobController'
import JobAssignmentController from './JobAssignmentController'
import JobNoteController from './JobNoteController'
import CustomerController from './CustomerController'
import InspectionController from './InspectionController'
import PhotoController from './PhotoController'
import RepairCompletionController from './RepairCompletionController'
import DynamicJobController from './DynamicJobController'
import Admin from './Admin'
import Settings from './Settings'
const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
JobController: Object.assign(JobController, JobController),
JobAssignmentController: Object.assign(JobAssignmentController, JobAssignmentController),
JobNoteController: Object.assign(JobNoteController, JobNoteController),
CustomerController: Object.assign(CustomerController, CustomerController),
InspectionController: Object.assign(InspectionController, InspectionController),
PhotoController: Object.assign(PhotoController, PhotoController),
RepairCompletionController: Object.assign(RepairCompletionController, RepairCompletionController),
DynamicJobController: Object.assign(DynamicJobController, DynamicJobController),
Admin: Object.assign(Admin, Admin),
Settings: Object.assign(Settings, Settings),
}

export default Controllers