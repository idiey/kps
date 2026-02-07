const inputBase = "h-10 w-full rounded-xl border border-[#E0E0E0] bg-white px-3 text-sm text-[#303438] placeholder:text-[#9A9A9A] shadow-sm outline-none transition focus:border-[#84C024] focus:ring-2 focus:ring-[#84C024]/20"
const selectBase = "h-10 w-full rounded-xl border border-[#E0E0E0] bg-white px-3 text-sm text-[#303438] shadow-sm outline-none transition focus:border-[#84C024] focus:ring-2 focus:ring-[#84C024]/20"

export const digitWorkshopTokens = {
  colors: {
    background: "#F8FCFC",
    card: "#FCFCFC",
    border: "#E0E0E0",
    text: "#303438",
    muted: "#646464",
    primary: "#84C024",
    primaryHover: "#76AD20",
    primarySoft: "#F0FCF4",
    warningBorder: "#F8F0A4",
    success: "#7FBC18",
    successSoft: "#EAF7EE",
  },
  radius: {
    card: "rounded-2xl",
    pill: "rounded-full",
  },
  shadow: {
    card: "shadow-sm shadow-black/5",
    hover: "hover:shadow-md hover:shadow-black/10",
  },
  spacing: {
    page: "px-6 py-6 lg:px-8 lg:py-8",
    section: "gap-6",
    card: "p-6",
  },
  typography: {
    pageTitle: "text-2xl font-semibold text-[#303438] tracking-tight",
    pageSubtitle: "text-sm text-[#646464]",
    sectionTitle: "text-lg font-semibold text-[#303438]",
    statNumber: "text-2xl font-semibold text-[#303438]",
    statLabel: "text-xs uppercase tracking-wide text-[#646464]",
  },
} as const

export const digitWorkshop = {
  layout: {
    pageWrapper: "min-h-screen bg-[#F8FCFC] text-[#303438]",
    contentContainer: "mx-auto w-full max-w-6xl px-6 py-6 lg:px-8 lg:py-8",
    sectionSpacing: "mt-6 flex flex-col gap-6",
    statsGrid: "grid gap-4 sm:grid-cols-2 lg:grid-cols-4",
  },
  sidebar: {
    sidebarWrapper: "h-full border-r border-[#E0E0E0] bg-[#FCFCFC]",
    sidebarBrand: "flex items-center gap-2 text-base font-semibold text-[#303438]",
    sidebarNav: "mt-4 flex flex-col gap-1",
    sidebarItem: "flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-[#545454] transition hover:bg-[#F0FCF4] hover:text-[#303438]",
    sidebarItemActive: "bg-[#EAF7EE] text-[#2E4A1B] shadow-sm",
    sidebarIcon: "text-[#84C024]",
    sidebarGroupLabel: "px-3 pt-4 text-[11px] font-semibold uppercase tracking-wide text-[#8A8A8A]",
    sidebarLogoChip: "flex size-9 items-center justify-center rounded-2xl bg-[#84C024] text-white shadow-sm",
  },
  typography: {
    pageTitle: "text-2xl font-semibold text-[#303438] tracking-tight",
    pageSubtitle: "text-sm text-[#646464]",
    sectionTitle: "text-lg font-semibold text-[#303438]",
    helperText: "text-xs text-[#7A7A7A]",
  },
  card: {
    cardBase: "rounded-2xl border border-[#E0E0E0] bg-[#FCFCFC] shadow-sm",
    cardMutedPanel: "rounded-2xl border border-[#CFE7D6] bg-[#F0FCF4] shadow-sm",
    cardGradientHero: "rounded-2xl border border-[#CFE7D6] bg-gradient-to-br from-[#F0FCF4] via-white to-[#F8FCFC] shadow-sm",
    cardGradientMint: "rounded-2xl border border-[#CFE7D6] bg-gradient-to-br from-[#EAF7EE] via-[#F5FFFA] to-[#F0FCF4] shadow-sm",
    cardGradientBlue: "rounded-2xl border border-[#D5E4FF] bg-gradient-to-br from-[#E8F0FF] via-white to-[#F7FAFF] shadow-sm",
    cardGradientYellow: "rounded-2xl border border-[#F3E6B5] bg-gradient-to-br from-[#FFF4D8] via-white to-[#FFFBF0] shadow-sm",
    statCard: "rounded-2xl border border-[#E0E0E0] bg-[#FCFCFC] p-5 shadow-sm",
    statNumber: "text-2xl font-semibold text-[#303438]",
    statLabel: "text-xs text-[#6B6B6B]",
    iconChip: "flex size-11 items-center justify-center rounded-2xl bg-[#84C024] text-white shadow-sm",
    iconChipSoft: "flex size-11 items-center justify-center rounded-2xl bg-[#EAF7EE] text-[#2E6B35] shadow-sm",
    iconChipBlue: "flex size-11 items-center justify-center rounded-2xl bg-[#E8F0FF] text-[#4A6FD8] shadow-sm",
    iconChipYellow: "flex size-11 items-center justify-center rounded-2xl bg-[#FFF4D8] text-[#C9821B] shadow-sm",
  },
  button: {
    btnPrimary: "inline-flex items-center justify-center gap-2 rounded-xl bg-[#84C024] px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-[#76AD20]",
    btnSecondary: "inline-flex items-center justify-center gap-2 rounded-xl border border-[#E0E0E0] bg-white px-4 py-2 text-sm font-medium text-[#303438] shadow-sm transition hover:bg-[#F8FCFC]",
    btnGhost: "inline-flex items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm text-[#545454] transition hover:bg-[#F0FCF4]",
    iconButton: "inline-flex size-9 items-center justify-center rounded-lg border border-[#E0E0E0] bg-white text-[#8A8A8A] transition hover:bg-[#F8FCFC] hover:text-[#84C024]",
    iconButtonDanger: "inline-flex size-9 items-center justify-center rounded-lg border border-[#F2D2D2] bg-white text-[#D14B4B] transition hover:bg-[#FBECEC]",
  },
  form: {
    inputBase,
    searchInput: `${inputBase} pl-9`,
    selectBase,
  },
  badge: {
    badgeBase: "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium",
    badgeMuted: "border border-[#E0E0E0] bg-[#F2F2F2] text-[#6B6B6B]",
    badgeSuccess: "border border-[#CFE7D6] bg-[#EAF7EE] text-[#2E6B35]",
    badgeOutline: "border border-[#E0E0E0] bg-white text-[#545454]",
    statusCompleted: "border border-[#CFE7D6] bg-[#EAF7EE] text-[#2E6B35]",
  },
  tabs: {
    tabsList: "inline-flex rounded-xl bg-[#F2F4F5] p-1",
    tabTrigger: "rounded-lg px-3 py-1.5 text-sm text-[#6B6B6B] transition data-[state=active]:bg-white data-[state=active]:text-[#303438] data-[state=active]:shadow-sm",
    tabTriggerActive: "bg-white text-[#303438] shadow-sm",
  },
  table: {
    tableWrapper: "overflow-hidden rounded-2xl border border-[#E0E0E0] bg-white",
    tableHead: "bg-[#F8FCFC] text-xs uppercase tracking-wide text-[#7A7A7A]",
    tableRow: "border-b border-[#EDEDED] transition hover:bg-[#F8FCFC]",
    tableCell: "px-4 py-3 text-sm text-[#545454]",
    tableActionIcon: "text-[#9A9A9A] transition hover:text-[#84C024]",
  },
  dialog: {
    dialogOverlay: "bg-black/30 backdrop-blur-sm",
    dialogContent: "rounded-2xl border border-[#E0E0E0] bg-white p-6 shadow-lg",
    dialogTitle: "text-lg font-semibold text-[#303438]",
    dialogSection: "mt-4 space-y-3",
    dialogFooter: "mt-6 flex items-center justify-end gap-3",
    noticeBox: "rounded-xl border border-[#F8F0A4] bg-[#FFFBEA] p-3 text-sm text-[#8A6B1F]",
  },
  selectable: {
    modeCard: "rounded-2xl border border-[#E0E0E0] bg-white p-4 transition hover:border-[#CFE7D6] hover:bg-[#F0FCF4]",
    modeCardSelected: "border-2 border-[#F8F0A4] bg-[#FFFBEA] shadow-sm",
    modeCardTitle: "text-sm font-semibold text-[#303438]",
    modeCardDescription: "text-xs text-[#6B6B6B]",
    modeCardMeta: "text-xs text-[#8A8A8A]",
  },
} as const
