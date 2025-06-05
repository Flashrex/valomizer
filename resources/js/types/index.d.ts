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
    id: number;
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
    id: number;
    uuid: string;
    active: boolean;
    displayName: string;
    gamemode: string;
    displayIcon: string;
    created_at: string;
    updated_at: string;
}
