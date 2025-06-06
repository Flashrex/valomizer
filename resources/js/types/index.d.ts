interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedResponse<T> {
    current_page: number;
    data: T[];
    first_page_url: string | null;
    from: number;
    last_page: number;
    last_page_url: string | null;
    links: Link[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface Agent {
    _id: number;
    uuid: string;
    active: boolean;
    displayName: string;
    description: string;
    developerName: string;
    releaseDate: string | null;
    displayIcon: string;
    displayIconSmall: string;
    bustPortrait: string;
    fullPortrait: string;
    fullPortraitV2: string;
    killfeedPortrait: string;
    background: string;
    backgroundGradientColors: string[];
    assetPath: string;
    isFullPortraitRightFacing: boolean;
    isPlayableCharacter: boolean;
    created_at: string;
    updated_at: string;
    role: { 
        uuid: string;
        displayName: string;
        description: string;
        displayIcon: string | null;
        assetPath: string | null;
    };
    selected: boolean | null;
}

export interface Map {
    _id: number;
    uuid: string;
    active: boolean;
    displayName: string;
    narrativeDescription: string;
    tacticalDescription: string;
    coordinates: string;
    displayIcon: string;
    listViewIcon: string;
    listViewIconTall: string;
    splash: string;
    stylizedBackgroundImage: string;
    assetPath: string;
    mapUrl: string;
    xMultiplier: number;
    yMultiplier: number;
    xScalarToAdd: number;
    yScalarToAdd: number;
    callouts: [
        {
            regionName: string;
            superRegionName: string;
            location: {
                x: number;
                y: number;
            };
        }
    ]
    gamemode: string;
    created_at: string;
    updated_at: string;
    selected: boolean | null;
    current: boolean | null;
    key: string | null;
    left: number | null;
    hidden: boolean | null;
}
