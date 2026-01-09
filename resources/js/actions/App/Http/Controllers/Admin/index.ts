import WorkflowController from './WorkflowController'
import WorkflowStatusController from './WorkflowStatusController'
import WorkflowTransitionController from './WorkflowTransitionController'
import TemplateController from './TemplateController'
import TemplateFieldController from './TemplateFieldController'
import RoleManagementController from './RoleManagementController'
const Admin = {
    WorkflowController: Object.assign(WorkflowController, WorkflowController),
WorkflowStatusController: Object.assign(WorkflowStatusController, WorkflowStatusController),
WorkflowTransitionController: Object.assign(WorkflowTransitionController, WorkflowTransitionController),
TemplateController: Object.assign(TemplateController, TemplateController),
TemplateFieldController: Object.assign(TemplateFieldController, TemplateFieldController),
RoleManagementController: Object.assign(RoleManagementController, RoleManagementController),
}

export default Admin