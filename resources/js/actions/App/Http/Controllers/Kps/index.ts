import DashboardController from './DashboardController'
import AnalyticsController from './AnalyticsController'
import SiteController from './SiteController'
import PenerokaController from './PenerokaController'
import DebtController from './DebtController'
import MonthlyDeductionController from './MonthlyDeductionController'
import AllocationReviewController from './AllocationReviewController'
import ReportController from './ReportController'
import AuditLogController from './AuditLogController'
const Kps = {
    DashboardController: Object.assign(DashboardController, DashboardController),
AnalyticsController: Object.assign(AnalyticsController, AnalyticsController),
SiteController: Object.assign(SiteController, SiteController),
PenerokaController: Object.assign(PenerokaController, PenerokaController),
DebtController: Object.assign(DebtController, DebtController),
MonthlyDeductionController: Object.assign(MonthlyDeductionController, MonthlyDeductionController),
AllocationReviewController: Object.assign(AllocationReviewController, AllocationReviewController),
ReportController: Object.assign(ReportController, ReportController),
AuditLogController: Object.assign(AuditLogController, AuditLogController),
}

export default Kps