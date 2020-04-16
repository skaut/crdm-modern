interface LiveReloadTarget {
	selector: string;
	media?: LiveReloadMediaRules;
	properties: Array<LiveReloadProperty>;
}
